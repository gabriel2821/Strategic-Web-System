<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Langkah;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Mail\ProgramReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\IncompleteProgramNotification;

class ProgramController extends Controller
{
    public function index(Langkah $langkah)
    {
        $programs = $langkah->programs;
        return view('programs.index', compact('langkah', 'programs'));
    }

    public function create(Langkah $langkah)
    {
        return view('programs.create', compact('langkah'));
    }

    public function store(Request $request, Langkah $langkah)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
        ]);

        $langkah->programs()->create($request->all());
        // return redirect()->route('teras.detail', $langkah->teras_id)->with('success', 'Program created successfully.');
        return redirect()->route('teras.detail', [$langkah->teras_id, 'langkah_id' => $langkah->id])->with('success', 'Strategi telah berjaya dicipta.');
    }

    public function edit(Langkah $langkah, Program $program)
    {
        return view('programs.edit', compact('langkah', 'program'));
    }

    public function update(Request $request, Langkah $langkah, Program $program)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
        ]);

        $program->update($request->all());
        return redirect()->route('teras.detail', $langkah->teras_id)->with('success', 'Strategi dikemas kini dengan jayanya.');
    }

    public function destroy(Langkah $langkah, Program $program)
    {
        $program->delete();
        return redirect()->route('teras.detail', $langkah->teras_id)->with('success', 'Strategi dipadamkan dengan jayanya.');
    }

    public function sendReminder(Program $program)
    {
        $incompleteRows = $program->programRows()->where('completion', '<', 100)->get();

        if ($incompleteRows->isEmpty()) {
            return back()->with('message', 'Semua tugas sudah selesai. Tiada peringatan dihantar.');
        }

        $user = $program->user;

        if (!$user) {
            return back()->with('error', 'Tiada pengguna yang ditugaskan kepada Strategi ini.');
        }

        $user->notify(new IncompleteProgramNotification($program, $incompleteRows));

        return back()->with('success', 'Notifikasi peringatan dihantar kepada ' . $user->email);
    }

    public function showAssignForm($langkahId, $programId)
    {
        $program = Program::findOrFail($programId);
        $langkah = Langkah::findOrFail($langkahId);
        $users = User::all(); // You can filter by role if needed

        return view('programs.assign', compact('program', 'langkah', 'users'));
    }

    public function assignUser(Request $request, $langkahId, $programId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $program = Program::findOrFail($programId);
        $program->user_id = $request->user_id;
        $program->save();

        return redirect()->route('programs.index', $langkahId)->with('message', 'Pengguna ditugaskan dengan jayanya.');
    }


}
