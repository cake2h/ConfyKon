<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Conference;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use App\Models\ParticipationType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Report;

use App\Models\Payment;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Illuminate\Validation\ValidationException;

class ConfController extends Controller
{
    public function main() 
    {
        return view('main');
    }

    public function index()
    {
        $conferences = Conference::with(['sections', 'format'])->get();
        $presentationTypes = DB::table('presentation_types')->get();
        return view('main.index', compact('conferences', 'presentationTypes'));
    }

    public function show(Conference $conference)
    {
        $sections = Section::where('conference_id', $conference->id)->get();
        return view('main.show', compact('conference', 'sections'));
    }

    public function destroy($id)
    {
        Conference::destroy($id);
        return redirect()->route('admin.index');
    }

    public function edit($id)
    {
        $conf = Conference::find($id);
        return view('admin.edit_conference', compact('conf'));
    }

    public function update(Request $request, $id)
    {
        $conf = Conference::find($id);
        if (!$conf) {
            return redirect()->back()->with('error', 'Конференция не найдена');
        }

        $conf->name = $request->input('name');
        $conf->address = $request->input('address');
        $conf->date_start = $request->input('date_start');
        $conf->date_end = $request->input('date_end');
        $conf->deadline_applications = $request->input('registration_deadline');
        $conf->publication_deadline = $request->input('publication_deadline');
        $conf->registration_deadline = $request->input('registration_deadline');
        $conf->description = $request->input('description');
        $conf->min_age = $request->input('no_min_age') ? null : $request->input('min_age');
        $conf->max_age = $request->input('no_max_age') ? null : $request->input('max_age');
        
        if (!$conf->update()) {
            return redirect()->back()->with('error', 'Ошибка при обновлении конференции');
        }

        return redirect()->route('admin.index');
    }

    public function add()
    {
        return view('admin.add_conference');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'deadline_applications' => 'required|date|before:date_start',
            'deadline_reports' => 'required|date|before:date_start',
            'city_id' => 'required|exists:cities,id',
            'format_id' => 'required|exists:formats,id',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0|gt:min_age',
            'education_levels' => 'required|array',
            'education_levels.*' => 'exists:education_levels,id'
        ]);

        $user = auth()->user();
        $conferencePrice = 300;

        dd($user->balance);

        // Проверка баланса
        if ($user->balance < $conferencePrice) {
            throw ValidationException::withMessages([
                'balance' => 'Недостаточно средств на счёте для создания конференции (нужно 300₽).',
            ]);
        }

        DB::transaction(function () use ($validated, $conferencePrice, $user, $request) {
            // Списываем деньги
            $user->decrement('balance', $conferencePrice);

            // Создаём локальный платеж (расход)
            Payment::create([
                'user_id' => $user->id,
                'type' => PaymentType::EXPENSE, // расход
                'amount' => $conferencePrice,
                'status' => PaymentStatus::SUCCESS,
                'comment' => 'Оплата за создание конференции',
            ]);

            // Создание конференции
            $validated['date_start'] = Carbon::parse($validated['date_start']);
            $validated['date_end'] = Carbon::parse($validated['date_end']);
            $validated['deadline_applications'] = Carbon::parse($validated['deadline_applications']);
            $validated['deadline_reports'] = Carbon::parse($validated['deadline_reports']);
            $validated['user_id'] = $user->id;

            $conf = Conference::create($validated);
            $conf->educationLevels()->attach($request->education_levels);
        });

        return redirect()->route('admin.index')
            ->with('success', 'Конференция успешно создана, с вашего счёта списано 300₽.');
    }

    public function application()
    {
        return view('main.subscribe');
    }

    public function subscribe(Request $request, Conference $conference)
    {
        try {
            Log::info('Начало регистрации на секцию', [
                'request_data' => $request->all(),
                'conference_id' => $conference->id,
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'section_id' => ['required', 'exists:sections,id'],
                'role_id' => ['required', 'exists:participation_types,id'],
                'presentation_type_id' => ['required', 'exists:presentation_types,id'],
            ]);

            // Если роль "Выступающий", добавляем обязательные поля
            $role = ParticipationType::find($request->role_id);
            Log::info('Информация о роли', [
                'role' => $role,
                'role_id' => $request->role_id
            ]);

            $report_id = null;
            if ($role && $role->id == 2) {
                $request->validate([
                    'title' => ['required', 'string', 'max:255'],
                    'contributors' => ['nullable', 'string', 'max:255'],
                ]);

                // Создаем запись в таблице reports
                $report = new Report();
                $report->report_theme = $request->title;
                $report->save();
                $report_id = $report->id;

                Log::info('Создан доклад', [
                    'report_id' => $report_id,
                    'title' => $request->title
                ]);
            }

            // Получаем ID статуса "На рассмотрении" (первый статус)
            $applicationStatus = DB::table('application_statuses')->first();
            if (!$applicationStatus) {
                throw new \Exception('Не найден статус заявки');
            }

            $data = [
                'section_id' => $request->section_id,
                'user_id' => Auth::id(),
                'presentation_type_id' => $request->presentation_type_id,
                'participation_type_id' => $request->role_id,
                'application_status_id' => $applicationStatus->id,
                'report_id' => $report_id
            ];

            if ($role && $role->id == 2) {
                $data['contributors'] = $request->contributors;
            }

            Log::info('Данные для создания заявки', [
                'data' => $data
            ]);

            $application = Application::create($data);

            Log::info('Заявка успешно создана', [
                'application_id' => $application->id,
                'user_id' => Auth::id(),
                'section_id' => $request->section_id
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Вы успешно зарегистрировались на секцию');

        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации на секцию', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Произошла ошибка при регистрации. Пожалуйста, попробуйте снова.');
        }
    }

    public function dock(Request $request)
    {
        Log::info('Начало загрузки файла', [
            'request_data' => $request->all(),
            'has_file' => $request->hasFile('file'),
            'application_id' => $request->application_id
        ]);

        try {
            $request->validate([
                'file' => 'required|file|mimes:doc,docx,pdf|max:10240',
                'application_id' => 'required|exists:applications,id'
            ]);

            $application = Application::findOrFail($request->application_id);
            
            // Получаем существующий доклад
            $report = $application->report;
            if (!$report) {
                throw new \Exception('Доклад не найден');
            }
            
            // Генерируем уникальное имя файла
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            
            // Перемещаем файл в директорию publications
            $request->file('file')->move(public_path('publications'), $fileName);
            
            // Сохраняем путь к файлу
            $report->file_path = 'publications/' . $fileName;
            $report->report_status_id = 1;
            $report->save();

            Log::info('Файл успешно загружен', [
                'application_id' => $request->application_id,
                'file_path' => $report->file_path
            ]);

            return redirect()->back()->with('success', 'Публикация успешно загружена');
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке файла', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Произошла ошибка при загрузке файла: ' . $e->getMessage());
        }
    }

    public function getSections(Conference $conference)
    {
        $sections = $conference->sections()->select('id', 'name')->get();
        if ($sections->isEmpty()) {
            return response()->json([]);
        }
        return response()->json($sections);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $month = $request->input('monthRange');
        
        $monthMap = [
            'Январь' => 1, 'Февраль' => 2, 'Март' => 3, 'Апрель' => 4, 'Май' => 5, 'Июнь' => 6,
            'Июль' => 7, 'Август' => 8, 'Сентябрь' => 9, 'Октябрь' => 10, 'Ноябрь' => 11, 'Декабрь' => 12
        ];

        $conferences = Conference::query();

        if ($query) {
            $conferences->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%']);
        }

        if (!empty($month)) {
            $parts = explode(' ', trim($month));

            if (count($parts) === 2) {
                $monthName = $parts[0];
                $year = (int) $parts[1];

                if (isset($monthMap[$monthName]) && $year > 2000) {
                    $monthNumber = $monthMap[$monthName];

                    $monthStart = "{$year}-" . str_pad($monthNumber, 2, '0', STR_PAD_LEFT) . "-01";
                    $monthEnd = date("Y-m-t", strtotime($monthStart));

                    $conferences->where(function ($query) use ($monthStart, $monthEnd) {
                        $query->whereBetween('date_start', [$monthStart, $monthEnd])
                            ->orWhereBetween('date_end', [$monthStart, $monthEnd])
                            ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                                $q->where('date_start', '<=', $monthStart)
                                    ->where('date_end', '>=', $monthEnd);
                            });
                    });
                }
            }
        }

        $conferences = $conferences->get();

        $presentationTypes = DB::table('presentation_types')->get();

        return view('main.index', compact('conferences', 'presentationTypes'));
    }

    public function showSections(Conference $conference)
    {
        $conference->load(['city', 'sections.moder', 'format']);
        $sections = $conference->sections;
        $presentationTypes = DB::table('presentation_types')->get();
        $roles = ParticipationType::all();
        $age = Auth::check() ? \Carbon\Carbon::parse(Auth::user()->birthday)->age : null;

        return view('main.sections', compact('conference', 'sections', 'presentationTypes', 'roles', 'age'));
    }

    public function editApplication($id)
    {
        $application = Application::with(['section.conference', 'participationType'])->findOrFail($id);
        
        // Проверяем, что заявка принадлежит текущему пользователю
        if ($application->user_id !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'У вас нет прав на редактирование этой заявки');
        }

        // Проверяем, что не прошла дата окончания регистрации
        if (now() >= \Carbon\Carbon::parse($application->section->conference->date_start)->subDays(3)) {
            return redirect()->route('dashboard.index')->with('error', 'Срок редактирования заявки истек');
        }

        $roles = ParticipationType::all();
        $presentationTypes = DB::table('presentation_types')->get();
        
        return view('main.edit_application', compact('application', 'roles', 'presentationTypes'));
    }

    public function updateApplication(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        // Проверяем, что заявка принадлежит текущему пользователю
        if ($application->user_id !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'У вас нет прав на редактирование этой заявки');
        }

        // Проверяем, что не прошла дата окончания регистрации
        if (now() >= \Carbon\Carbon::parse($application->section->conference->date_start)->subDays(3)) {
            return redirect()->route('dashboard.index')->with('error', 'Срок редактирования заявки истек');
        }

        $request->validate([
            'participation_type_id' => ['required'],
            'presentation_type_id' => ['required'],
        ]);

        $data = [
            'participation_type_id' => $request->participation_type_id,
            'presentation_type_id' => $request->presentation_type_id,
        ];

        if ($request->participation_type_id != 1) {
            $request->validate([
                'name' => ['required', 'string'],
                'contributors' => ['string', 'nullable'],
            ]);
            $data['contributors'] = $request->contributors;

            // Обновляем или создаем доклад
            if ($application->report) {
                $application->report->update(['report_theme' => $request->name]);
            } else {
                $report = new Report();
                $report->report_theme = $request->name;
                $report->save();
                $data['report_id'] = $report->id;
            }
        } else {
            $data['contributors'] = null;
            // Если есть доклад, удаляем его
            if ($application->report) {
                $application->report->delete();
                $data['report_id'] = null;
            }
        }

        if (!$application->update($data)) {
            return redirect()->back()->with('error', 'Ошибка при обновлении заявки');
        }

        return redirect()->route('dashboard.index')->with('success', 'Заявка успешно обновлена');
    }

    public function deleteApplication($id)
    {
        $application = Application::findOrFail($id);
        
        // Проверяем, что заявка принадлежит текущему пользователю
        if ($application->user_id !== Auth::id()) {
            return redirect()->route('dashboard.index')->with('error', 'У вас нет прав на удаление этой заявки');
        }

        // Проверяем, что не прошла дата окончания регистрации
        if (now() >= \Carbon\Carbon::parse($application->section->conference->date_start)->subDays(3)) {
            return redirect()->route('dashboard.index')->with('error', 'Срок удаления заявки истек');
        }

        $application->delete();

        return redirect()->route('dashboard.index')->with('success', 'Заявка успешно удалена');
    }

    public function showFAQ(Conference $conference)
    {
        return view('main.faq', compact('conference'));
    }
}
