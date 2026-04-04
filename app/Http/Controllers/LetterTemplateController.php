<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterTemplate;

class LetterTemplateController extends Controller
{
    public function index()
    {
        $templates = LetterTemplate::all();
        return view('letter-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('letter-templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:letter_templates',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'type' => 'required|in:official,memo,notice',
        ]);

        LetterTemplate::create($request->all());
        return redirect()->route('letter-templates.index')->with('success', 'Template created.');
    }

    public function show(LetterTemplate $letterTemplate)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($letterTemplate);
        }
        return view('letter-templates.show', compact('letterTemplate'));
    }

    public function edit(LetterTemplate $letterTemplate)
    {
        return view('letter-templates.edit', compact('letterTemplate'));
    }

    public function update(Request $request, LetterTemplate $letterTemplate)
    {
        $request->validate([
            'name' => 'required|unique:letter_templates,name,' . $letterTemplate->id,
            'description' => 'nullable|string',
            'content' => 'required|string',
            'type' => 'required|in:official,memo,notice',
        ]);

        $letterTemplate->update($request->all());
        return redirect()->route('letter-templates.index')->with('success', 'Template updated.');
    }

    public function destroy(LetterTemplate $letterTemplate)
    {
        $letterTemplate->delete();
        return redirect()->route('letter-templates.index')->with('success', 'Template deleted.');
    }
}
