<?php

namespace App\Http\Controllers;

use App\Models\Teras;
use App\Models\Langkah;
use Illuminate\Http\Request;

class LangkahController extends Controller
{
    public function index(Teras $teras)
    {
        $langkah = $teras->langkah;
        return view('langkah.index', compact('teras', 'langkah'));
    }

    public function create(Teras $teras)
    {
        return view('langkah.create', compact('teras'));
    }

    public function store(Request $request, Teras $teras)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $teras->langkah()->create($request->all());
        return redirect()->route('langkah.index', $teras)->with('success', 'KRA telah berjaya dicipta.');
    }

    public function edit(Teras $teras, Langkah $langkah)
    {
        return view('langkah.edit', compact('teras', 'langkah'));
    }

    public function update(Request $request, Teras $teras, Langkah $langkah)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $langkah->update($request->all());
        return redirect()->route('langkah.index', $teras)->with('success', 'Langkah telah dikemas kini dengan jayanya.');
    }

    public function destroy(Teras $teras, Langkah $langkah)
    {
        $langkah->delete();
        return redirect()->route('langkah.index', $teras)->with('success', 'Langkah telah dipadamkan dengan jayanya.');
    }
}
