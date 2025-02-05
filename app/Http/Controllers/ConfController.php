<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Conf;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;

class ConfController extends Controller
{
    public function index()
    {
        $conferences = Conf::all();
        $presentationTypes = DB::table('presentation_types')->get();
        return view('main.index', compact('conferences', 'presentationTypes'));
    }

    public function show(Conf $conference)
    {
        $sections = $conference->sections;
        return view('main.show', compact('conference', 'sections'));
    }

    public function destroy($id)
    {
        Conf::destroy($id);
        return redirect()->route('admin.index');
    }

    public function edit($id)
    {
        $conf = Conf::find($id);
        return view('admin.edit_conference', compact('conf'));
    }

    public function update(Request $request, $id)
    {
        $conf = Conf::find($id);

        $conf->name = $request->input('name');
        $conf->address = $request->input('address');
        $conf->date_start = $request->input('date_start');
        $conf->date_end = $request->input('date_end');
        $conf->deadline = $request->input('deadline');
        $conf->description = $request->input('description');
        $conf->update();

        return redirect()->route('admin.index');
    }

    public function add()
    {
        return view('admin.add_conference');
    }

    public function store(Request $request)
    {
        $conf = new Conf();

        $conf->name = $request->input('name');
        $conf->address = $request->input('address');
        $conf->date_start = $request->input('date_start');
        $conf->date_end = $request->input('date_end');
        $conf->deadline = $request->input('deadline');
        $conf->description = $request->input('description');
        $conf->save();

        return redirect()->route('admin.index');
    }

    public function application()
    {
        return view('main.subscribe');
    }

    public function subscribe(Request $request, Conf $conference)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'section_id' => ['required'],
        ]);

        Application::create([
            'name' => $request->name,
            'status' => 0,
            'otherAuthors' => $request->otherAuthors,
            'user_id' => Auth::user()->id,
            'section_id' => $request->section_id,
            'type_id' => $request->presentation_type_id
        ]);

        return redirect()->route('dashboard.index');
    }

    public function dock(Request $request)
    {
        $request->validate([
            'file' => ['required'],
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('publications'), $fileName);

            $application = Application::findOrFail($request->application_id);

            $application->update([
                'file_path' => 'publications/' . $fileName,
            ]);

            $section = Section::find($application->section_id);
            $moder = User::find($section->moder_id);
            $email = $moder->email;

            Mail::send([], [], function($message) use ($email) {
                $message->to($email)
                    ->subject('Новая статья на конференции МИМ-2024')
                    ->from('stud0000264064@utmn.ru', 'Организатор конференции МИМ-2024')
                    ->text('Загружена новая статья в вашей секции');
            });
        }
        return redirect()->route('dashboard.index');
    }

    public function getSections(Conf $conference)
    {
        $sections = $conference->sections()->select('id', 'name')->get();
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

        $conferences = Conf::query();

        if ($query) {
            $conferences->where('name', 'LIKE', "%{$query}%");
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
}
