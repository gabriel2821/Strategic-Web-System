<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Teras;
use App\Models\ProgramRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProgramRowController extends Controller
{
    public function create(Program $program)
    {
        return view('program_rows.create', compact('program'));
    }

    public function index(Program $program)
    {
        // Calculate average completion percentage for each status
        $statusSummary = ProgramRow::where('program_id', $program->id)
            ->select('status', DB::raw('AVG(completion) as average_completion'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('program_rows.index', compact('program', 'statusSummary'));
    }

    public function store(Request $request, Program $program)
    {
        $request->validate([
            'inisiatif.*' => 'nullable|string',
            'peneraju_utama.*' => 'nullable|string',
            'tahun_mula_siap.*' => 'nullable|string|max:50',
            'petunjuk_prestasi.*' => 'nullable|string',
            'pencapaian.*' => 'nullable|string',
            'status.*' => 'required|string',
            'completion.*' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->inisiatif as $index => $inisiatif) {
        $program->programRows()->create([
            'inisiatif' => $inisiatif,
            'peneraju_utama' => $request->peneraju_utama[$index],
            'tahun_mula_siap' => $request->tahun_mula_siap[$index],
            'petunjuk_prestasi' => $request->petunjuk_prestasi[$index],
            'pencapaian' => $request->pencapaian[$index],
            'status' => $request->status[$index],
            'completion' => $request->completion[$index],
        ]);
    }
        return redirect()->route('program_rows.index', $program->langkah->teras_id)->with('success', 'Program Row telah berjaya dicipta.');
    }

    public function edit(Program $program, ProgramRow $row)
    {
        return view('program_rows.edit', compact('program', 'row'));
    }

    public function update(Request $request, Program $program, ProgramRow $row)
    {
        $request->validate([
            'inisiatif' => 'nullable|string',
            'peneraju_utama' => 'nullable|string',
            'tahun_mula_siap' => 'nullable|string|max:50',
            'petunjuk_prestasi' => 'nullable|string',
            'pencapaian' => 'nullable|string',
            'status' => 'required|string',
            'completion' => 'required|integer|min:0|max:100',
        ]);

        $row->update($request->all());
        return redirect()->route('program_rows.index', $program->langkah->teras_id)->with('success', 'Program Row telah dikemas kini dengan jayanya.');
    }

    public function destroy(Program $program, ProgramRow $row)
    {
        $row->delete();
        return redirect()->route('teras.detail', $program->langkah->teras_id)->with('success', 'Program Row telah dipadam dengan jayanya.');
    }

    // public function report(Request $request)
    // {
    //     $query = ProgramRow::with('program.langkah.teras');

    //     // Filter by status
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // Filter by program name (from related program)
    //     if ($request->filled('search')) {
    //         $query->whereHas('program', function ($q) use ($request) {
    //             $q->where('program_name', 'like', '%' . $request->search . '%');
    //         });
    //     }

    //     $programs = $query->get();

    //     return view('reports.index', compact('programs'));
    // }

    public function report(Request $request)
    {
        $query = Program::with(['langkah.teras', 'programRows']);

        // Filter by program name
        if ($request->filled('search')) {
            $query->where('program_name', 'like', '%' . $request->search . '%');
        }

        // Filter by status (only programs that have at least one matching row)
        if ($request->filled('status')) {
            $query->whereHas('programRows', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter by Teras
        if ($request->filled('teras') && $request->teras != '') {
            $query->whereHas('langkah.teras', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->teras . '%');
            });
        }

        // Filter by month and year from tahun_mula_siap (assuming format like "YYYY-MM" or "YYYY")
        if ($request->filled('month')) {
            $query->whereHas('programRows', function ($q) use ($request) {
                $q->whereMonth('created_at', $request->month);
            });
        }
        if ($request->filled('year')) {
            $query->whereHas('programRows', function ($q) use ($request) {
                $q->whereYear('created_at', $request->year);
            });
        }

        $programs = $query->get();

        // Eager load only filtered programRows for each program
        if ($request->filled('status')) {
            $programs->load(['programRows' => function ($q) use ($request) {
                $q->where('status', $request->status);
            }]);
        } else {
            $programs->load('programRows');
        }

        // Group programs by Teras
        $terasPrograms = [];
        foreach ($programs as $program) {
            $terasId = $program->langkah->teras->id ?? 'unknown';
            if (!isset($terasPrograms[$terasId])) {
                $terasPrograms[$terasId] = [
                    'teras' => $program->langkah->teras,
                    'programs' => collect(),
                ];
            }
            $terasPrograms[$terasId]['programs']->push($program);
        }

        return view('reports.index', compact('terasPrograms'));
    }

}
