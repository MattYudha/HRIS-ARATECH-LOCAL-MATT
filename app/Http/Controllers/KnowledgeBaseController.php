<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $articles = KnowledgeBase::orderBy('created_at', 'desc')->get();
        $categories = $articles->groupBy('category');
        return view('knowledge-base.index', compact('categories', 'articles'));
    }

    public function create()
    {
        return view('knowledge-base.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required',
            'keywords' => 'nullable|string',
        ]);

        KnowledgeBase::create([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'keywords' => $request->keywords,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('knowledge-base.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $article = KnowledgeBase::findOrFail($id);
        return view('knowledge-base.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required',
            'keywords' => 'nullable|string',
        ]);

        $article = KnowledgeBase::findOrFail($id);
        $article->update($request->all());

        return redirect()->route('knowledge-base.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $article = KnowledgeBase::findOrFail($id);
        $article->delete();

        return redirect()->route('knowledge-base.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
