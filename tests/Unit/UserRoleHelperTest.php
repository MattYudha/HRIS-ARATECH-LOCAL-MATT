<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Constants\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleHelperTest extends TestCase
{
    // use RefreshDatabase; // Don't use refresh to keep existing data
    
    public function test_user_role_helpers()
    {
        // Find or create a Power User
        $powerRole = Role::where('title', Roles::POWER_USER)->first();
        if (!$powerRole) {
            $this->fail('Power User role not found');
        }
        
        $user = User::whereHas('employee', function($q) use ($powerRole) {
            $q->where('role_id', $powerRole->id);
        })->first();
        
        if (!$user) {
            $this->markTestSkipped('No Power User found for testing');
        }
        
        $this->assertTrue($user->isPowerUser());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->isSupervisor());
        
        echo "\nUser " . $user->name . " is Power User: OK";
    }
}
