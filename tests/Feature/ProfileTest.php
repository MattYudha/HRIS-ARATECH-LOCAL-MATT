<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Constants\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /**
     * Test that the profile edit page is rendered correctly with restricted sections removed.
     */
    public function test_profile_edit_page_sections_rendering(): void
    {
        // Find a user with an employee record
        $user = User::whereHas('employee')->first();
        
        if (!$user) {
            $this->markTestSkipped('No user with employee record found for testing.');
        }

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);

        // Should contain
        $response->assertSee('Employee Information'); // Should be present
        $response->assertSee('Update Password');       // Should be present

        // Should NOT contain (restricted sections)
        $response->assertDontSee('Profile Information'); // Updated section name/email should be gone
        $response->assertDontSee('Delete Account');       // Self-deletion should be gone
        $response->assertDontSee('Once your account is deleted');
    }

    /**
     * Test that password can be updated.
     */
    public function test_password_can_be_updated(): void
    {
        // Find a user by email to have a known password (if we can't seed, we use a known one or create temporarily)
        // For whitebox testing on existing DB, we might need a test user.
        // Let's create a temporary user for this specific test if using RefreshDatabase is risky.
        
        $user = User::factory()->create([
            'password' => \Illuminate\Support\Facades\Hash::make('current-password'),
        ]);

        $response = $this->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'current-password',
                'password' => 'new-password-1234',
                'password_confirmation' => 'new-password-1234',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password-1234', $user->refresh()->password));
        
        // Clean up
        $user->delete();
    }
}
