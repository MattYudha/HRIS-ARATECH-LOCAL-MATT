<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $role = session('role');
            $query = LeaveRequest::with('employee');
            
            if (in_array($role, ['HR Administrator', 'Super Admin'])) {
                // Full access
            } elseif ($role === 'Manager / Unit Head') {
                $deptId = auth()->user()->employee->department_id;
                $query->whereHas('employee', function($q) use ($deptId) {
                    $q->where('department_id', $deptId);
                });
            } else {
                $query->where('employee_id', session('employee_id'));
            }
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('leave-requests.show', $row->id).'" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    $btns .= '<a href="'.route('leave-requests.edit', $row->id).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';
                    
                    $role = session('role');
                    $canApprove = in_array($role, ['HR Administrator', 'Super Admin', 'Manager / Unit Head']);
                    
                    if($row->status == 'pending' && $canApprove){
                        $btns .= '<a href="'.url('leave-requests/confirm/'.$row->id).'" class="btn btn-outline-success"><i class="bi bi-check-lg"></i></a>';
                        $btns .= '<a href="'.url('leave-requests/reject/'.$row->id).'" class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>';
                    }
                    
                    $btns .= '</div>';
                    return $btns;
                })
                ->addColumn('status_badge', function($row){
                    $class = match($row->status) {
                        'confirmed' => 'bg-success',
                        'pending' => 'bg-warning text-dark',
                        'rejected' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->editColumn('start_date', function($row){
                    return $row->start_date ? \Carbon\Carbon::parse($row->start_date)->format('d M Y') : '-';
                })
                ->editColumn('end_date', function($row){
                    return $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d M Y') : '-';
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        
        return view('leave_requests.index');
    }

    public function create()
    {
        $employees = Employee::all();

        return view('leave_requests.create', compact('employees'));
    }

    public function store(Request $request)
    {
        if (!in_array(session('role'), ['HR Administrator', 'Super Admin'])) {
            // Kalau bukan HR Administrator/Super Admin, maka employee_id diambil dari session.
            $request->merge(['employee_id' => session('employee_id')]);
        }

        // Ketika pertama kali membuat request cuti, statusnya adalah pending
        $request->merge(['status' => 'pending']);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        LeaveRequest::create($request->all());

        return redirect()->route('leave-requests.index')->with('success', 'Leave Request Created Successfully');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $employees = Employee::all();
        $statuses = ['pending', 'confirmed', 'rejected'];

        return view('leave_requests.edit', compact('leaveRequest', 'employees', 'statuses'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $role = session('role');
        $isAdminOrManager / Unit Head = in_array($role, ['HR Administrator', 'Super Admin', 'Manager / Unit Head']);

        $rules = [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        if ($isAdminOrManager / Unit Head) {
            $rules['employee_id'] = 'required|exists:employees,id';
            $rules['status'] = 'required|string';
        }

        $request->validate($rules);

        $data = $request->except(['status', 'employee_id']);

        if ($isAdminOrManager / Unit Head) {
            $data['status'] = $request->status;
            $data['employee_id'] = $request->employee_id;
        } else {
            // Regular employee can only update their own request details
            // We force employee_id to be theirs just in case, and ignore status change
            $data['employee_id'] = session('employee_id');
        }

        $leaveRequest->update($data);

        return redirect()->route('leave-requests.index')->with('success', 'Leave Request Updated Successfully');
    }

    public function confirm(int $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        
        if ($leaveRequest->status === 'confirmed') {
            return redirect()->route('leave-requests.index')->with('error', 'Leave request is already confirmed.');
        }

        $startDate = Carbon::parse($leaveRequest->start_date);
        $endDate = Carbon::parse($leaveRequest->end_date);
        $duration = $startDate->diffInDays($endDate) + 1; // Include end date

        $employee = $leaveRequest->employee;
        $balance = $employee->getLeaveBalance($leaveRequest->leave_type);

        if ($balance->balance < $duration) {
            return redirect()->route('leave-requests.index')->with('error', 'Insufficient leave balance. Available: ' . $balance->balance . ' days.');
        }

        // Update balance
        $balance->taken += $duration;
        $balance->balance -= $duration;
        $balance->save();

        $leaveRequest->update([
            'status' => 'confirmed',
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request confirmed and balance updated.');
    }

    public function reject(int $id)
    {
        LeaveRequest::findOrFail($id)->update([
            'status' => 'rejected',
        ]);
        
        return redirect()->route('leave-requests.index')->with('success', 'Leave Request Updated Successfully');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Leave Request Deleted Successfully');
    }
}
