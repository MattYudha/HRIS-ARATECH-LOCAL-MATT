<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    // Display list of departments
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::with(['manager', 'employees'])->withCount('employees');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('manager_name', function($row){
                    return $row->manager ? $row->manager->fullname : '-';
                })
                ->addColumn('employees_count', function($row){
                    return $row->employees_count;
                })
                ->addColumn('action', function($row){
                    $btns = '<div class="btn-group btn-group-sm" role="group">';
                    $btns .= '<a href="'.route('departments.show', $row->id).'" class="btn btn-info text-white"><i class="bi bi-eye"></i></a>';
                    $btns .= '<a href="'.route('departments.edit', $row->id).'" class="btn btn-warning"><i class="bi bi-pencil"></i></a>';
                    
                    $csrf = csrf_token();
                    $method = method_field('DELETE');
                    $btns .= '
                        <form action="'.route('departments.destroy', $row->id).'" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="'.$csrf.'">
                            '.$method.'
                            <button type="submit" class="btn btn-danger" onclick="return confirm(\'Delete this department?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    ';
                    $btns .= '</div>';
                    return $btns;
                })
                ->editColumn('status', function($row){
                     $class = match($row->status) {
                        'active' => 'bg-success',
                        'inactive' => 'bg-secondary',
                        default => 'bg-info'
                    };
                    return '<span class="badge '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('departments.index');
    }

    // Show the form for creating a new department
    public function create()
    {
        $employees = Employee::orderBy('fullname')->get();
        $departments = $this->getDepartmentTree();
        return view('departments.create', compact('employees', 'departments'));
    }

    // Store a newly created department in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    // Display the specified department
    public function show($id)
    {
        $department = Department::with(['manager', 'parent', 'children', 'employees'])->findOrFail($id);
        return view('departments.show', compact('department'));
    }

    // Show the form for editing the specified department
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $employees = Employee::orderBy('fullname')->get();
        $departments = $this->getDepartmentTree(null, 0, [$id]); // Prevent self-parenting
        return view('departments.edit', compact('department', 'employees', 'departments'));
    }

    // Update the specified department in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id|different:id',
        ]);

        $department = Department::findOrFail($id);
        
        // Prevent circular dependency
        if ($request->filled('parent_id') && $department->hasDescendant($request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Cannot set parent to a descendant department, as it would create a circular dependency.'])->withInput();
        }

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    // Remove the specified department from storage
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        
        // Safe Deletion Check
        if ($department->employees()->exists()) {
             return redirect()->route('departments.index')->with('error', 'Cannot delete department: It has assigned employees.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    // Display Organizational Chart
    public function orgChart()
    {
        $departments = Department::with(['manager', 'employees'])->get();
        return view('departments.org_chart', compact('departments'));
    }

    /**
     * Helper to get departments in a hierarchical list for dropdowns.
     */
    private function getDepartmentTree($parentId = null, $level = 0, $excludeIds = [])
    {
        $results = [];
        $departments = Department::where('parent_id', $parentId);
        
        if (!empty($excludeIds)) {
            $departments = $departments->whereNotIn('id', $excludeIds);
        }
        
        $departments = $departments->orderBy('name')->get();

        foreach ($departments as $dept) {
            $prefix = str_repeat('— ', $level);
            $dept->name = $prefix . $dept->name;
            $results[] = $dept;
            
            // Recursively get children
            $children = $this->getDepartmentTree($dept->id, $level + 1, $excludeIds);
            $results = array_merge($results, $children);
        }

        return $results;
    }
}
