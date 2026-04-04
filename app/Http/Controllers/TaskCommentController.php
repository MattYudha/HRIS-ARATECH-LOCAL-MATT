<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $employeeId = session('employee_id');
        
        if (!$employeeId) {
            return redirect()->back()->with('error', 'Session tidak valid. Silakan login ulang.');
        }

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $filename = time() . '_' . $file->getClientOriginalName();
            $evidencePath = $file->storeAs('task_evidence', $filename, 'public');
        }

        TaskComment::create([
            'task_id' => $task->id,
            'employee_id' => $employeeId,
            'comment' => $request->comment,
            'evidence_path' => $evidencePath,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Display the evidence attachment for the specified comment.
     */
    public function evidence(TaskComment $comment)
    {
        abort_unless($comment->evidence_path, 404);

        if (!Storage::disk('public')->exists($comment->evidence_path)) {
            abort(404);
        }

        return Storage::disk('public')->response(
            $comment->evidence_path,
            basename($comment->evidence_path)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskComment $comment)
    {
        return redirect()->back()->with('error', 'Komentar tidak dapat dihapus, hanya bisa dikutip.');
    }
}
