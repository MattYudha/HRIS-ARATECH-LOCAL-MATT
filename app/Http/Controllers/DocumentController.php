<?php

namespace App\Http\Controllers;

use App\Models\DocumentIdentity;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $request->validate([
            'identity_type_id' => 'required|exists:identity_types,identity_type_id',
            'identity_number' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('employee_documents/' . $employee->id, $fileName, 'public');

        DocumentIdentity::create([
            'employee_id' => $employee->id,
            'identity_type_id' => $request->identity_type_id,
            'identity_number' => $request->identity_number,
            'file_name' => $fileName,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy(DocumentIdentity $document)
    {
        // Delete physical file
        Storage::disk('public')->delete('employee_documents/' . $document->employee_id . '/' . $document->file_name);
        
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
