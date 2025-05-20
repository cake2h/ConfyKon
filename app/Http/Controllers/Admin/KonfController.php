<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Conference;
use App\Models\City;
use App\Models\Format;
use App\Models\EducationLevel;
use App\Models\Faq;
use App\Models\Section;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// Svejak
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class KonfController extends Controller
{
    public function create()
    {
        $cities = City::orderBy('name')->get();
        $formats = Format::orderBy('name')->get();
        $educationLevels = EducationLevel::orderBy('name')->get();
        $questionThemes = \App\Models\QuestionTheme::all();
        return view('admin.konfs.add', compact('cities', 'formats', 'educationLevels', 'questionThemes'));
    }

    public function store(Request $request)
    {
        Log::info('Request data:', [
            'all' => $request->all(),
            'files' => $request->allFiles(),
            'has_files' => $request->hasFile('files'),
            'file_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
            'has_temp_faqs' => session()->has('temp_faqs'),
            'temp_faqs' => session('temp_faqs')
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            'deadline_applications' => 'required|date',
            'deadline_reports' => 'required|date',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'format_id' => 'required|exists:formats,id',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'file_names' => 'nullable|array',
            'file_names.*' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except(['education_levels', 'files', 'file_names']);
            
            // Проверяем, что если указаны оба возраста, то max_age > min_age
            if (!empty($data['min_age']) && !empty($data['max_age']) && $data['max_age'] <= $data['min_age']) {
                throw new \Exception('Максимальный возраст должен быть больше минимального');
            }
            
            $data['user_id'] = auth()->id();
            
            $conference = Conference::create($data);
            
            Log::info('Conference created:', [
                'id' => $conference->id,
                'name' => $conference->name
            ]);

            $conference->educationLevels()->attach($request->education_levels);

            // Сохраняем FAQ из сессии
            if (session()->has('temp_faqs')) {
                Log::info('Saving FAQs from session');
                foreach (session('temp_faqs') as $faq) {
                    try {
                        $createdFaq = $conference->faqs()->create([
                            'name' => $faq['name'],
                            'answer' => $faq['answer'],
                            'question_theme_id' => $faq['question_theme_id']
                        ]);
                        Log::info('FAQ created:', [
                            'id' => $createdFaq->id,
                            'name' => $createdFaq->name,
                            'theme_id' => $createdFaq->question_theme_id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error creating FAQ:', [
                            'error' => $e->getMessage(),
                            'faq_data' => $faq
                        ]);
                        throw $e;
                    }
                }
                session()->forget('temp_faqs');
            } else {
                Log::info('No FAQs in session');
            }

            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $fileNames = $request->input('file_names', []);
                
                Log::info('Files to process:', [
                    'count' => count($files),
                    'names' => $fileNames
                ]);

                // Создаем директорию, если она не существует
                if (!file_exists(public_path('uploads/conferences'))) {
                    mkdir(public_path('uploads/conferences'), 0777, true);
                }

                foreach ($files as $index => $file) {
                    if ($file && $file->isValid()) {
                        try {
                            // Проверяем тип файла
                            $mimeType = $file->getMimeType();
                            $allowedTypes = [
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            ];
                            
                            Log::info('Processing file:', [
                                'index' => $index,
                                'original_name' => $file->getClientOriginalName(),
                                'mime_type' => $mimeType,
                                'size' => $file->getSize()
                            ]);

                            if (!in_array($mimeType, $allowedTypes)) {
                                throw new \Exception('Недопустимый тип файла. Разрешены только PDF и DOC/DOCX файлы.');
                            }

                            $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                            $filePath = 'uploads/conferences/' . $fileName;

                            // Перемещаем файл
                            if (!$file->move(public_path('uploads/conferences'), $fileName)) {
                                throw new \Exception('Не удалось сохранить файл');
                            }

                            // Создаем запись в базе данных
                            $conference->files()->create([
                                'name' => $fileNames[$index] ?? $file->getClientOriginalName(),
                                'file_path' => $filePath
                            ]);

                            Log::info('File successfully processed:', [
                                'path' => $filePath,
                                'name' => $fileNames[$index] ?? $file->getClientOriginalName()
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error processing file:', [
                                'error' => $e->getMessage(),
                                'file' => $file->getClientOriginalName()
                            ]);
                            throw $e;
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Конференция успешно создана');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при создании конференции: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при создании конференции: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Conference $konf)
    {
        try {
            DB::beginTransaction();

            $data = $request->except(['education_levels', 'files', 'file_names', 'delete_files', 'existing_files']);
            
            // Проверяем, что если указаны оба возраста, то max_age > min_age
            if (!empty($data['min_age']) && !empty($data['max_age']) && $data['max_age'] <= $data['min_age']) {
                throw new \Exception('Максимальный возраст должен быть больше минимального');
            }

            $konf->update($data);
            
            $konf->educationLevels()->sync($request->education_levels);

            // Обработка удаления файлов
            if ($request->has('delete_files')) {
                foreach ($request->delete_files as $fileId) {
                    $file = $konf->files()->find($fileId);
                    if ($file) {
                        // Удаляем физический файл
                        if (file_exists(public_path($file->file_path))) {
                            unlink(public_path($file->file_path));
                        }
                        // Удаляем запись из базы данных
                        $file->delete();
                    }
                }
            }

            // Обработка существующих файлов
            if ($request->has('existing_files')) {
                foreach ($request->existing_files as $index => $fileId) {
                    $file = $konf->files()->find($fileId);
                    if ($file) {
                        // Обновляем название файла
                        $file->name = $request->file_names[$index];
                        $file->save();

                        // Если загружен новый файл, заменяем старый
                        if ($request->hasFile('files') && isset($request->file('files')[$index])) {
                            $newFile = $request->file('files')[$index];
                            if ($newFile && $newFile->isValid()) {
                                // Удаляем старый файл
                                if (file_exists(public_path($file->file_path))) {
                                    unlink(public_path($file->file_path));
                                }

                                // Сохраняем новый файл
                                $fileName = time() . '_' . $index . '_' . $newFile->getClientOriginalName();
                                $filePath = 'uploads/conferences/' . $fileName;
                                $newFile->move(public_path('uploads/conferences'), $fileName);

                                // Обновляем путь к файлу в базе данных
                                $file->file_path = $filePath;
                                $file->save();
                            }
                        }
                    }
                }
            }

            // Обработка новых файлов
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $fileNames = $request->input('file_names', []);
                
                foreach ($files as $index => $file) {
                    if ($file && $file->isValid() && !isset($request->existing_files[$index])) {
                        // Проверяем тип файла
                        $mimeType = $file->getMimeType();
                        $allowedTypes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ];
                        
                        if (!in_array($mimeType, $allowedTypes)) {
                            throw new \Exception('Недопустимый тип файла. Разрешены только PDF и DOC/DOCX файлы.');
                        }

                        $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                        $filePath = 'uploads/conferences/' . $fileName;

                        // Перемещаем файл
                        if (!$file->move(public_path('uploads/conferences'), $fileName)) {
                            throw new \Exception('Не удалось сохранить файл');
                        }

                        // Создаем запись в базе данных
                        $konf->files()->create([
                            'name' => $fileNames[$index] ?? $file->getClientOriginalName(),
                            'file_path' => $filePath
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Конференция успешно обновлена');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при обновлении конференции: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при обновлении конференции: ' . $e->getMessage());
        }
    }

    public function edit(Conference $konf)
    {
        $cities = City::all();
        $formats = Format::all();
        $educationLevels = EducationLevel::all();
        $konf->load('educationLevels');
        
        return view('admin.konfs.edit', compact('konf', 'cities', 'formats', 'educationLevels'));
    }

    public function destroy(Conference $konf)
    {
        try {
            $konf->delete();
            return redirect()->route('admin.index')
                ->with('success', 'Конференция успешно удалена');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Произошла ошибка при удалении конференции: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $conferences = Conference::with(['city', 'format', 'organizer'])->get();
        return view('admin.conferences.index', compact('conferences'));
    }

    public function getFiles(Conference $konf)
    {
        $files = $konf->files()->get();
        return response()->json($files);
    }

    public function downloadFile($id)
    {
        $file = \App\Models\File::findOrFail($id);
        $path = public_path($file->file_path);
        
        if (!file_exists($path)) {
            return back()->with('error', 'Файл не найден');
        }

        return response()->download($path, $file->name);
    }

    public function showFaqForm($themeId)
    {
        $theme = \App\Models\QuestionTheme::findOrFail($themeId);
        return view('admin.konfs.faq', compact('theme'));
    }

    public function storeFaq(Request $request, $themeId)
    {
        Log::info('Storing FAQ:', [
            'request_data' => $request->all(),
            'theme_id' => $themeId,
            'session_id' => session()->getId()
        ]);

        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'required|string|max:255',
            'answers' => 'required|array',
            'answers.*' => 'required|string|max:255',
        ]);

        $faqs = [];
        foreach ($request->questions as $index => $question) {
            $faqs[] = [
                'name' => $question,
                'answer' => $request->answers[$index],
                'question_theme_id' => $themeId
            ];
        }

        Log::info('Prepared FAQs:', [
            'faqs' => $faqs
        ]);

        // Сохраняем FAQ в сессию
        session(['temp_faqs' => $faqs]);

        Log::info('FAQs saved to session:', [
            'session_id' => session()->getId(),
            'has_temp_faqs' => session()->has('temp_faqs'),
            'temp_faqs' => session('temp_faqs')
        ]);

        return redirect()->route('admin.konfs.create')->with('success', 'FAQ успешно добавлены');
    }

    public function editFaq(Conference $konf)
    {
        $faqs = $konf->faqs()->with('theme')->get();
        $questionThemes = \App\Models\QuestionTheme::all();
        return view('admin.konfs.edit_faq', compact('konf', 'faqs', 'questionThemes'));
    }

    public function updateFaq(Request $request, Conference $konf)
    {
        Log::info('Updating FAQ:', [
            'request_data' => $request->all(),
            'conference_id' => $konf->id
        ]);

        $request->validate([
            'faqs' => 'required|array',
            'faqs.*.id' => 'nullable|exists:faq,id',
            'faqs.*.name' => 'required|string|max:255',
            'faqs.*.answer' => 'required|string|max:255',
            'faqs.*.question_theme_id' => 'required|exists:question_themes,id'
        ]);

        try {
            DB::beginTransaction();

            // Обновляем существующие FAQ
            foreach ($request->faqs as $faqData) {
                if (isset($faqData['id'])) {
                    // Обновляем существующий FAQ
                    $faq = $konf->faqs()->find($faqData['id']);
                    if ($faq) {
                        $faq->update([
                            'name' => $faqData['name'],
                            'answer' => $faqData['answer'],
                            'question_theme_id' => $faqData['question_theme_id']
                        ]);
                    }
                } else {
                    // Создаем новый FAQ
                    $konf->faqs()->create([
                        'name' => $faqData['name'],
                        'answer' => $faqData['answer'],
                        'question_theme_id' => $faqData['question_theme_id']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.konfs.edit.faq', $konf->id)
                ->with('success', 'FAQ успешно обновлены');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при обновлении FAQ: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при обновлении FAQ: ' . $e->getMessage());
        }
    }

    public function deleteFaq(Conference $konf, Faq $faq)
    {
        try {
            if ($faq->conference_id === $konf->id) {
                $faq->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'FAQ не принадлежит данной конференции'], 403);
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении FAQ: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Произошла ошибка при удалении FAQ'], 500);
        }
    }

    public function downloadPDF(Conference $konf)
    {
        $sections = Section::where('conference_id', $konf->id)
            ->withCount(['applications as participants_count'])
            ->get();

        $totalParticipants = $sections->sum('participants_count');

        $sections = $sections->map(function ($section) use ($totalParticipants) {
            $percentage = $totalParticipants > 0
                ? round(($section->participants_count / $totalParticipants) * 100, 2)
                : 0;

            return [
                'name' => $section->name,
                'participants_count' => $section->participants_count,
                'percentage' => $percentage,
            ];
        });

        $educationStats = DB::table('applications')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('sections', 'applications.section_id', '=', 'sections.id')
            ->join('education_levels', 'users.education_level_id', '=', 'education_levels.id')
            ->where('sections.conference_id', $konf->id)
            ->select('education_levels.name', DB::raw('COUNT(*) as count'))
            ->groupBy('education_levels.name')
            ->get();

        $total = $educationStats->sum('count');

        $educationStats = $educationStats->map(function ($item) use ($total) {
            $item->percentage = $total > 0 ? round(($item->count / $total) * 100, 2) : 0;
            return $item;
        });

         $studyPlaceStats = DB::table('applications')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('sections', 'applications.section_id', '=', 'sections.id')
            ->join('study_places', 'users.study_place_id', '=', 'study_places.id')
            ->where('sections.conference_id', $konf->id)
            ->select('study_places.name', DB::raw('COUNT(*) as count'))
            ->groupBy('study_places.name')
            ->get();

        $total = $studyPlaceStats->sum('count');

        $studyPlaceStats = $studyPlaceStats->map(function ($item) use ($total) {
            $item->percentage = $total > 0 ? round(($item->count / $total) * 100, 2) : 0;
            return $item;
        });

        $pdf = Pdf::loadView('pdf.report', [
            'educationStats' => $educationStats,
            'sections' => $sections,
            'studyPlaceStats' => $studyPlaceStats
        ]);

        return $pdf->download('test.pdf');
    }
} 