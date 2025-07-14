<?php

namespace App\Http\Controllers;

use App\Models\Teras;
use App\Models\Archive;
use App\Models\Langkah;
use App\Models\Program;
use App\Models\ProgramRow;
use App\Models\ArchivedTeras;
use Illuminate\Http\Request;
use App\Models\ArchivedProgram;
use App\Models\ArchivedLangkah;
use App\Models\ArchivedProgramRow;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{

    public function index()
    {
        $archives = Archive::latest()->get();
        return view('admin.archive.index', compact('archives'));
    }

    public function show(Archive $archive)
    {
        $archivedTeras = $archive->archivedTeras()->with('langkah.programs.programRows')->get();

        return view('admin.archive.show', compact('archive', 'archivedTeras'));
    }
    
    public function create()
    {
        $archives = Archive::withCount('archivedteras')->latest()->get();
        return view('admin.archive.create', compact('archives'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        DB::transaction(function () use ($request) {
            $archive = Archive::create(['name' => $request->name]);

            foreach (Teras::with('langkah.programs.programRows')->get() as $teras) {
                $archivedTeras = ArchivedTeras::create([
                    'name' => $teras->name,
                    'description' => $teras->description,
                    'archive_id' => $archive->id
                ]);

                foreach ($teras->langkah as $langkah) {
                    $archivedLangkah = ArchivedLangkah::create([
                        'name' => $langkah->name,
                        'description' => $langkah->description,
                        'teras_id' => $archivedTeras->id,
                        'archive_id' => $archive->id
                    ]);

                    foreach ($langkah->programs as $program) {
                        $archivedProgram = ArchivedProgram::create([
                            'program_name' => $program->program_name,
                            'langkah_id' => $archivedLangkah->id,
                            'user_id' => $program->user_id,
                            'archive_id' => $archive->id
                        ]);

                        foreach ($program->programRows as $row) {
                            ArchivedProgramRow::create([
                                'program_id' => $archivedProgram->id,
                                'inisiatif' => $row->inisiatif,
                                'peneraju_utama' => $row->peneraju_utama,
                                'tahun_mula_siap' => $row->tahun_mula_siap,
                                'petunjuk_prestasi' => $row->petunjuk_prestasi,
                                'pencapaian' => $row->pencapaian,
                                'status' => $row->status,
                                'completion' => $row->completion,
                                'archive_id' => $archive->id
                            ]);
                        }
                    }
                }
            }

            // Optional: clear original tables
            ProgramRow::query()->delete();
            Program::query()->delete();
            Langkah::query()->delete();
            Teras::query()->delete();
        });

        return redirect()->route('archives.create')->with('success', 'Pengarkiban selesai dan data diset semula.');
    }

    public function restore(Archive $archive)
    {
        // Clear current data
        DB::transaction(function () use ($archive) {
            \App\Models\Teras::query()->delete();
            \App\Models\Langkah::query()->delete();
            \App\Models\Program::query()->delete();
            \App\Models\ProgramRow::query()->delete();

            // Restore from archive
            foreach ($archive->archivedTeras as $archivedTeras) {
                $newTeras = \App\Models\Teras::create([
                    'name' => $archivedTeras->name,
                    'description' => $archivedTeras->description,
                ]);

                foreach ($archivedTeras->langkah as $archivedLangkah) {
                    $newLangkah = \App\Models\Langkah::create([
                        'teras_id' => $newTeras->id,
                        'name' => $archivedLangkah->name,
                        'description' => $archivedLangkah->description,
                    ]);

                    foreach ($archivedLangkah->programs as $archivedProgram) {
                        $newProgram = \App\Models\Program::create([
                            'langkah_id' => $newLangkah->id,
                            'program_name' => $archivedProgram->program_name,
                            'user_id' => $archivedProgram->user_id,
                        ]);

                        foreach ($archivedProgram->programRows as $archivedRow) {
                            \App\Models\ProgramRow::create([
                                'program_id' => $newProgram->id,
                                'inisiatif' => $archivedRow->inisiatif,
                                'peneraju_utama' => $archivedRow->peneraju_utama,
                                'tahun_mula_siap' => $archivedRow->tahun_mula_siap,
                                'petunjuk_prestasi' => $archivedRow->petunjuk_prestasi,
                                'pencapaian' => $archivedRow->pencapaian,
                                'status' => $archivedRow->status,
                                'completion' => $archivedRow->completion,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('archives.index')->with('message', 'Arkib telah dipulihkan dengan jayanya.');
    }
}
