<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KonfController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Store request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'registration_deadline' => 'required|date|before:date_start',
            'deadline' => 'required|date|before:date_start'
        ]);

        Log::info('Validated data:', $validated);

        $validated['registration_deadline'] = Carbon::parse($validated['registration_deadline']);
        $validated['date_start'] = Carbon::parse($validated['date_start']);
        $validated['date_end'] = Carbon::parse($validated['date_end']);
        $validated['deadline'] = Carbon::parse($validated['deadline']);

        $konf = Konf::create($validated);
        Log::info('Created konf:', $konf->toArray());

        return redirect()->route('admin.konfs.index')
            ->with('success', 'Конференция успешно создана');
    }

    public function update(Request $request, Konf $konf)
    {
        Log::info('Update request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'registration_deadline' => 'required|date|before:date_start',
            'deadline' => 'required|date|before:date_start'
        ]);

        Log::info('Validated data:', $validated);

        $validated['registration_deadline'] = Carbon::parse($validated['registration_deadline']);
        $validated['date_start'] = Carbon::parse($validated['date_start']);
        $validated['date_end'] = Carbon::parse($validated['date_end']);
        $validated['deadline'] = Carbon::parse($validated['deadline']);

        $konf->update($validated);
        Log::info('Updated konf:', $konf->toArray());

        return redirect()->route('admin.konfs.index')
            ->with('success', 'Конференция успешно обновлена');
    }
} 