<?php

namespace App\Http\Controllers;

use App\Models\EmployeeUpdateApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EmployeeUpdateApprovalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EmployeeUpdateApproval::with(['employee', 'requester'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm">';
                    $btns .= '<a href="'.route('employee-approvals.show', $row->id).'" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'pending' => 'bg-warning',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }

        return view('employee-approvals.index');
    }

    public function show($id)
    {
        $approval = EmployeeUpdateApproval::with(['employee', 'requester'])->findOrFail($id);
        return view('employee-approvals.show', compact('approval'));
    }

    public function approve(Request $request, $id)
    {
        $approval = EmployeeUpdateApproval::findOrFail($id);

        if ($approval->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        DB::beginTransaction();
        try {
            $employee = $approval->employee;
            $employee->update($approval->new_data);

            $approval->update([
                'status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->route('employee-approvals.index')->with('success', 'Profile update approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving update: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'refusal_reason' => 'required|string|max:255'
        ]);

        $approval = EmployeeUpdateApproval::findOrFail($id);

        if ($approval->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $approval->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'refusal_reason' => $request->refusal_reason
        ]);

        return redirect()->route('employee-approvals.index')->with('success', 'Profile update rejected.');
    }
}
