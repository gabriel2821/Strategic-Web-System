<?php

namespace App\Http\Controllers;

use App\Models\Teras;
use App\Models\Program;
use Illuminate\Http\Request;

class TerasController extends Controller
{
    // Display all Teras records
    public function index()
    {
        $teras = Teras::all(); // Fetch all rows from the database
        return view('teras.index', compact('teras')); // Pass data to view
    }

    // Show form to create a new Teras
    public function create()
    {
        return view('teras.create');
    }

    // Store a new Teras record into the database
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Create a new Teras record
        Teras::create($request->all());

        // Redirect to the index with a success message
        return redirect()->route('teras.index')->with('success', 'Teras telah berjaya dicipta.');
    }

    // Show form to edit an existing Teras
    public function edit(Teras $teras)
    {
        return view('teras.edit', compact('teras'));
    }

    // Update the specified Teras in the database
    public function update(Request $request, Teras $teras)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Update the Teras record
        $teras->update($request->all());

        // Redirect with success message
        return redirect()->route('teras.index')->with('success', 'Teras telah dikemas kini dengan jayanya.');
    }

    // Delete the specified Teras from the database
    public function destroy(Teras $teras)
    {
        $teras->delete(); // Delete the record
        return redirect()->route('teras.index')->with('success', 'Teras telah dipadam dengan jayanya.');
    }

    public function detail(Teras $teras)
    {
        $selectedLangkah = null;
        $programs = collect();
        if (request()->query('langkah_id')) {
            $selectedLangkah = $teras->langkah->find(request()->query('langkah_id'));
            if ($selectedLangkah) {
                $programs = $selectedLangkah->programs;
            }
        }
        return view('teras.detail', compact('teras', 'selectedLangkah', 'programs'));
    }

}