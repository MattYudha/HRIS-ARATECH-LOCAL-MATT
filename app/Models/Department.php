<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the table name if it's not plural
    protected $table = 'departments';

    // Define the fillable attributes (columns that can be mass-assigned)
    protected $fillable = [
        'name',
        'description',
        'status',
        'manager_id',
        'parent_id',
    ];

    
    /**
     * Relationship: A Department belongs to a Manager / Unit Head (Employee).
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Relationship: A Department belongs to a Parent Department.
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Relationship: A Department has many Sub-Departments (Children).
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Relationship: A Department has many Employees.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Check if a given department ID is a descendant of this department.
     */
    public function hasDescendant($departmentId)
    {
        foreach ($this->children as $child) {
            if ($child->id == $departmentId || $child->hasDescendant($departmentId)) {
                return true;
            }
        }
        return false;
    }
}