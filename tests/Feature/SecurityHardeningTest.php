<?php

namespace Tests\Feature;

use App\Constants\Roles;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Models\Letter;
use App\Models\Payroll;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
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
    public function test_common_employee_cannot_view_others_letters()
    {
        $employee1 = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        $employee2 = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        
        $letter2ByEmployee2 = Letter::create([
            'user_id' => $employee2->user->id,
            'subject' => 'Secret Letter',
            'content' => 'Top Secret Content',
            'letter_type' => 'memo',
            'status' => 'draft'
        ]);

        // Employee 1 tries to view Employee 2's letter
        $response = $this->actingAsEmployee($employee1->user, $employee1)
            ->get(route('letters.show', $letter2ByEmployee2->id));
        
        $response->assertStatus(403);
    }

    /** @test */
    public function test_letter_mass_assignment_protection()
    {
        $employee = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        $admin = $this->createEmployee(Roles::HR);

        $letter = Letter::create([
            'user_id' => $employee->user->id,
            'subject' => 'My Draft',
            'content' => 'Initial Content',
            'letter_type' => 'memo',
            'status' => 'draft'
        ]);

        // Attempt to self-approve via mass assignment in update
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->put(route('letters.update', $letter->id), [
                'subject' => 'Hehe I am approved now',
                'content' => 'Modified content',
                'letter_type' => 'memo',
                'status' => 'approved', // INJECTION
                'approver_id' => $admin->user->id // INJECTION
            ]);

        $letter->refresh();
        $this->assertEquals('draft', $letter->status);
        $this->assertNull($letter->approver_id);
        $this->assertEquals('Hehe I am approved now', $letter->subject);
    }

    /** @test */
    public function test_common_employee_cannot_delete_payroll()
    {
        $employee = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'period_month' => 1,
            'period_year' => 2026,
            'salary' => 5000,
            'net_salary' => 5000,
            'status' => 'paid',
            'pay_date' => now()
        ]);

        $response = $this->actingAsEmployee($employee->user, $employee)
            ->delete(route('payrolls.destroy', $payroll->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('payroll', ['id' => $payroll->id], 'sqlite');
    }

    /** @test */
    public function test_common_employee_cannot_manage_incidents()
    {
        $employee = $this->createEmployee(Roles::COMMON_EMPLOYEE);
        $manager = $this->createEmployee(Roles::MANAGER);

        $incident = Incident::create([
            'employee_id' => $employee->id,
            'type' => 'warning',
            'incident_date' => now(),
            'description' => 'Test Incident',
            'severity' => 'low',
            'status' => 'pending',
            'reported_by' => $manager->user->id
        ]);

        // Edit
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->get(route('incidents.edit', $incident->id));
        $response->assertStatus(403);

        // Update
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->put(route('incidents.update', $incident->id), [
                'employee_id' => $employee->id,
                'status' => 'resolved'
            ]);
        $response->assertStatus(403);

        // Delete
        $response = $this->actingAsEmployee($employee->user, $employee)
            ->delete(route('incidents.destroy', $incident->id));
        $response->assertStatus(403);
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
            'birth_date' => now()->subYears(20),
            'hire_date' => now()
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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('status');
            $table->timestamps();
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
            $table->date('birth_date')->nullable();
            $table->date('hire_date')->nullable();
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

        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->unsignedBigInteger('letter_template_id')->nullable();
            $table->string('letter_number')->nullable();
            $table->string('subject');
            $table->text('content');
            $table->string('letter_type');
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('period_month');
            $table->integer('period_year');
            $table->decimal('salary', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->string('status');
            $table->date('pay_date');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('type');
            $table->date('incident_date');
            $table->text('description');
            $table->string('severity');
            $table->string('status');
            $table->unsignedBigInteger('reported_by');
            $table->timestamps();
        });
    }
}
