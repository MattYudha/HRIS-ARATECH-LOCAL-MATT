<?php

namespace Tests\Feature;

use App\Constants\Roles;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\InventoryUsageLog;
use App\Models\KnowledgeBase;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Procurement;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditFixesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        app('db')->purge('sqlite');
        app('db')->setDefaultConnection('sqlite');
        app('db')->reconnect('sqlite');

        $this->createTestingSchema();
        // Disable ONLY CSRF to keep the Role middleware active
        $this->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
    }

    /** @test */
    public function test_leave_balance_safeguard()
    {
        $employee = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'status' => 'pending',
            'reason' => 'Testing'
        ]);

        LeaveBalance::create([
            'employee_id' => $employee->id,
            'leave_type' => 'annual',
            'balance' => 0, // Insufficient
            'year' => now()->year
        ]);

        $hr = $this->createEmployee(Roles::HR);
        // Leave confirm is a GET request in this app
        $response = $this->actingAsEmployee($hr->user, $hr)
            ->get(route('leave-requests.confirm', $leaveRequest->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Insufficient leave balance. Available: 0 days.');
        $this->assertEquals('pending', $leaveRequest->fresh()->status);
    }

    /** @test */
    public function test_department_circular_dependency_safeguard()
    {
        $dept1 = Department::create(['name' => 'Dept 1', 'status' => 'active']);
        $dept2 = Department::create(['name' => 'Dept 2', 'status' => 'active', 'parent_id' => $dept1->id]);
        
        $hr = $this->createEmployee(Roles::POWER_USER);
        $response = $this->actingAsEmployee($hr->user, $hr)
            ->put(route('departments.update', $dept1->id), [
                'name' => 'Dept 1 Updated',
                'parent_id' => $dept2->id, // Circular!
                'status' => 'active'
            ]);

        $response->assertRedirect();
        // Verify the exact error message from DepartmentController
        $response->assertSessionHasErrors(['parent_id' => 'Cannot set parent to a descendant department, as it would create a circular dependency.']);
        $this->assertEquals(null, $dept1->fresh()->parent_id);
    }

    /** @test */
    public function test_inventory_usage_log_ownership_security()
    {
        $dept1 = Department::create(['name' => 'Dept 1', 'status' => 'active']);
        $dept2 = Department::create(['name' => 'Dept 2', 'status' => 'active']);
        
        $manager1 = $this->createEmployee(Roles::MANAGER, $dept1->id);
        $employee2 = $this->createEmployee(Roles::COMMON_EMPLOYEE, $dept2->id);
        
        // Match real table schema: no sku, use min_stock_threshold
        $inventory = InventoryCategory::create(['name' => 'Cats ' . uniqid()])->inventories()->create([
            'name' => 'Item', 
            'quantity' => 1, 
            'min_stock_threshold' => 0,
            'status' => 'active',
            'item_type' => 'tidak_habis_pakai'
        ]);
        
        $log = InventoryUsageLog::create([
            'inventory_id' => $inventory->id,
            'employee_id' => $employee2->id,
            'borrowed_date' => now(),
            'status' => 'borrowed'
        ]);

        // Manager 1 should NOT be able to see/edit Log of Employee 2 (different dept)
        $response = $this->actingAsEmployee($manager1->user, $manager1)
            ->get(route('inventory-usage-logs.show', $log->id));
        $response->assertStatus(403);

        $response = $this->actingAsEmployee($manager1->user, $manager1)
            ->get(route('inventory-usage-logs.edit', $log->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_knowledge_base_access_control()
    {
        $employee = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        
        // Read access should be OK
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->get(route('knowledge-base.index'));
        $response->assertOk();

        // Write access should be FORBIDDEN
        // The middleware is role:HR,Power User
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->get(route('knowledge-base.create'));
        $response->assertStatus(403);

        $response = $this->actingAsEmployee($employee->user, $employee)
            ->post(route('knowledge-base.store'), ['title' => 'T', 'category' => 'C', 'content' => 'CX']);
        $response->assertStatus(403);
    }

    /** @test */
    public function test_vendor_deletion_safeguard()
    {
        $vendor = Vendor::create(['name' => 'V', 'status' => 'active']);
        Procurement::create([
            'vendor_id' => $vendor->id,
            'employee_id' => 1,
            'po_number' => 'PO1',
            'order_date' => now(),
            'status' => 'pending',
            'total_amount' => 100
        ]);

        $hr = $this->createEmployee(Roles::HR);
        $response = $this->actingAsEmployee($hr->user, $hr)
            ->delete(route('vendors.destroy', $vendor->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete vendor: It has associated procurement records.');
        $this->assertDatabaseHas('vendors', ['id' => $vendor->id], 'sqlite');
    }

    // Helper methods
    private function createEmployee($roleTitle, $departmentId = null)
    {
        $suffix = uniqid();
        $role = Role::create(['title' => $roleTitle, 'description' => $roleTitle]);
        $dept = $departmentId ?: Department::create(['name' => 'D ' . $suffix, 'status' => 'active'])->id;
        
        $employee = Employee::create([
            'nik' => 'N-' . $suffix,
            'fullname' => 'F ' . $suffix,
            'email' => "e$suffix@test.com",
            'phone_number' => '1',
            'address' => 'A',
            'department_id' => $dept,
            'role_id' => $role->id,
            'salary' => 1,
            'status' => 'active',
            'npwp' => 'NPWP-' . $suffix,
        ]);

        $user = User::factory()->create([
            'email' => $employee->email,
            'employee_id' => $employee->id,
        ]);
        
        $employee->user = $user;
        return $employee;
    }

    private function actingAsEmployee($user, $employee)
    {
        return $this->actingAs($user)->withSession([
            'role' => $employee->role->title,
            'employee_id' => $employee->id,
        ]);
    }

    private function createTestingSchema()
    {
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('access')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('npwp')->unique();
            $table->string('phone_number');
            $table->text('address');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->decimal('salary', 10, 2);
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('leave_type');
            $table->integer('balance');
            $table->integer('year');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_category_id');
            $table->string('item_type')->default('tidak_habis_pakai');
            $table->string('name');
            $table->integer('quantity');
            $table->integer('min_stock_threshold');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inventory_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamp('borrowed_date');
            $table->timestamp('returned_date')->nullable();
            $table->string('status')->default('borrowed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('po_number')->unique();
            $table->date('order_date');
            $table->string('status');
            $table->decimal('total_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('content');
            $table->string('keywords')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
