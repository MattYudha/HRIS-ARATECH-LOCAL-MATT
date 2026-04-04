<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterArchive;

class LetterArchiveController extends Controller
{
    public function index()
    {
        $archives = LetterArchive::orderByDesc('year')->orderByDesc('month')->get();
        return view('letter-archives.index', compact('archives'));
    }

    public function show(LetterArchive $letterArchive)
    {
        return view('letter-archives.show', compact('letterArchive'));
    }
}
