<?php

namespace Tests\Feature;

use App\Constants\Roles;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Presence;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WfoOfficeSitePresenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('cache.default', 'array');
        config()->set('session.driver', 'array');
        app('db')->purge('sqlite');
        app('db')->setDefaultConnection('sqlite');
        app('db')->reconnect('sqlite');

        $this->createTestingSchema();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_presence_create_page_renders_requested_wfo_sites_and_ssids(): void
    {
        $sites = $this->createRequestedWfoSites();
        [$user, $employee] = $this->createEmployeeUserForPresence($sites[0]);

        $response = $this->actingAsEmployee($user, $employee)->get(route('presences.create'));

        $response->assertOk();
        $response->assertSee('name="office_location_id"', false);
        $response->assertSee('Marquee - The Plaza Office Tower');
        $response->assertSee('Cilandak Town Square');
        $response->assertSee('Kantor TEST');
        $response->assertSee('MARQUEE');
        $response->assertSee('CITOS');
        $response->assertSee('SERHAN');
    }

    public function test_any_employee_can_check_in_wfo_from_each_requested_site_and_record_selected_office_location(): void
    {
        $sites = $this->createRequestedWfoSites();
        [$user, $employee] = $this->createEmployeeUserForPresence($sites[0]);

        $baseTime = Carbon::create(2026, 3, 24, 8, 5, 0);

        foreach ($sites as $index => $site) {
            Carbon::setTestNow($baseTime->copy()->addDays($index));

            $response = $this->actingAsEmployee($user, $employee)->post(route('presences.store'), [
                'work_type' => 'WFO',
                'office_location_id' => $site->id,
                'fingerprint' => 'test-fingerprint',
                'is_mobile' => '0',
                'latitude' => $site->latitude,
                'longitude' => $site->longitude,
                'accuracy' => 15,
                'ssid' => $site->allowed_ssids[0],
                '_token' => 'test-token',
            ]);

            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('success', 'Presensi berhasil dicatat.');

            $this->assertDatabaseHas('presences', [
                'employee_id' => $employee->id,
                'office_location_id' => $site->id,
                'work_type' => 'WFO',
                'date' => Carbon::now()->format('Y-m-d'),
                'status' => 'present',
            ], 'sqlite');
        }
    }

    public function test_presence_index_ajax_returns_recorded_wfo_office_site_name(): void
    {
        $sites = $this->createRequestedWfoSites();
        $site = $sites[0];
        [$user, $employee] = $this->createEmployeeUserForPresence($site);

        Presence::create([
            'employee_id' => $employee->id,
            'office_location_id' => $site->id,
            'check_in' => Carbon::create(2026, 3, 24, 8, 0, 0)->format('Y-m-d H:i:s'),
            'date' => '2026-03-24',
            'status' => 'present',
            'work_type' => 'WFO',
            'latitude' => $site->latitude,
            'longitude' => $site->longitude,
        ]);

        $response = $this->actingAsEmployee($user, $employee)->get(route('presences.index', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'office_location_name' => 'Marquee - The Plaza Office Tower',
        ]);
    }

    private function actingAsEmployee(User $user, Employee $employee): self
    {
        return $this->actingAs($user)->withSession([
            '_token' => 'test-token',
            'role' => $employee->role->title,
            'employee_id' => $employee->id,
        ]);
    }

    private function createEmployeeUserForPresence(OfficeLocation $homeOffice): array
    {
        $suffix = uniqid();

        $department = Department::create([
            'name' => 'QA Test Department ' . $suffix,
            'description' => 'Department for WFO office site tests',
            'status' => 'active',
        ]);

        $role = Role::create([
            'title' => Roles::COMMON_EMPLOYEE,
            'description' => 'Default common employee role for tests',
        ]);

        $employee = Employee::create([
            'nik' => 'NIK-' . $suffix,
            'npwp' => 'NPWP-' . $suffix,
            'fullname' => 'WFO Test Employee ' . $suffix,
            'email' => 'wfo-test-' . $suffix . '@example.com',
            'phone_number' => '081234567890',
            'address' => 'Alamat test WFO',
            'birth_date' => Carbon::create(1995, 1, 1)->format('Y-m-d H:i:s'),
            'hire_date' => Carbon::create(2024, 1, 1)->format('Y-m-d H:i:s'),
            'department_id' => $department->id,
            'office_location_id' => $homeOffice->id,
            'role_id' => $role->id,
            'status' => 'active',
            'employee_status' => 'permanent',
            'salary' => 5000000,
        ]);

        $user = User::factory()->create([
            'name' => $employee->fullname,
            'email' => $employee->email,
            'employee_id' => $employee->id,
            'browser_fingerprint_desktop' => 'test-fingerprint',
        ]);

        return [$user, $employee];
    }

    private function createRequestedWfoSites(): array
    {
        return [
            OfficeLocation::create([
                'name' => 'Marquee - The Plaza Office Tower',
                'location_type' => 'branch',
                'address' => 'RR4C+WV Gondangdia, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta',
                'latitude' => -6.192553,
                'longitude' => 106.822353,
                'radius' => 1000,
                'allowed_ssids' => ['MARQUEE'],
                'status' => 'active',
                'notes' => 'Site test WFO 1',
            ]),
            OfficeLocation::create([
                'name' => 'Cilandak Town Square',
                'location_type' => 'branch',
                'address' => 'PQ5X+FR Cilandak Bar., Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta',
                'latitude' => -6.291389,
                'longitude' => 106.799722,
                'radius' => 1000,
                'allowed_ssids' => ['CITOS'],
                'status' => 'active',
                'notes' => 'Site test WFO 2',
            ]),
            OfficeLocation::create([
                'name' => 'Kantor TEST',
                'location_type' => 'other',
                'address' => 'Lokasi uji WFO internal',
                'latitude' => -6.367914,
                'longitude' => 106.644239,
                'radius' => 1000,
                'allowed_ssids' => ['SERHAN'],
                'status' => 'active',
                'notes' => 'Site test WFO 3',
            ]),
        ];
    }

    private function createTestingSchema(): void
    {
        Schema::dropAllTables();

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
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

        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('location_type')->default('other');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius')->default(1000);
            $table->json('allowed_ssids')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code')->nullable();
            $table->string('nik')->unique();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('npwp')->unique();
            $table->string('place_of_birth')->nullable();
            $table->string('phone_number');
            $table->text('address');
            $table->timestamp('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->timestamp('hire_date')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('office_location_id')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->string('status');
            $table->string('employee_status')->default('permanent');
            $table->unsignedBigInteger('foundation_id')->nullable();
            $table->unsignedBigInteger('education_level_id')->nullable();
            $table->decimal('salary', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('browser_fingerprint_desktop')->nullable();
            $table->string('browser_fingerprint_mobile')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('office_location_id')->nullable();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('work_type')->default('WFO');
            $table->date('date');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suspicious_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('activity_type');
            $table->text('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }
}
