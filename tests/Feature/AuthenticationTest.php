<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Subscription;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_with_email_and_password(): void
    {
        $user = User::create([
            'full_name' => 'Test Student',
            'email' => 'student@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'student',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_subscription_success_handles_string_duration_and_activates_premium(): void
    {
        $user = User::create([
            'full_name' => 'Test Student',
            'email' => 'pay@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'student',
            'academic_background' => 'HSC',
            'preferred_field' => 'Computer Science',
            'cgpa' => 3.70,
            'language_proficiency' => 'IELTS 6.5',
            'degree_seeking' => 'bachelor',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->user_id,
            'plan' => 'premium',
            'duration_months' => '3',
            'amount_bdt' => 799,
            'payment_method' => 'sslcommerz',
            'transaction_id' => 'SC-TEST-XYZ',
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('student.subscription.success', $subscription));

        $response->assertRedirect(route('student.subscription.index'));
        $this->assertDatabaseHas('subscriptions', [
            'subscription_id' => $subscription->subscription_id,
            'status' => 'completed',
        ]);
        $user->refresh();
        $this->assertSame('premium', $user->plan);
    }

    public function test_recommendation_page_generates_results_for_complete_profile(): void
    {
        $user = User::create([
            'full_name' => 'Test Student',
            'email' => 'rec@example.com',
            'password_hash' => Hash::make('secret123'),
            'role' => 'student',
            'academic_background' => 'HSC',
            'preferred_field' => 'Computer Science',
            'cgpa' => 3.70,
            'language_proficiency' => 'IELTS 6.5',
            'degree_seeking' => 'bachelor',
        ]);

        $university = University::create([
            'university_name' => 'Sample University',
            'city' => 'Dhaka',
            'province' => 'Dhaka',
            'region' => 'Dhaka',
            'ranking_tier' => '211',
            'language_of_instruction' => 'English',
            'established_year' => 2000,
            'website_url' => 'https://example.com',
            'description' => 'Sample',
            'is_active' => true,
        ]);

        Program::create([
            'university_id' => $university->university_id,
            'program_name' => 'Computer Science and Engineering',
            'degree_level' => 'bachelor',
            'field_of_study' => 'Computer Science',
            'duration' => '4 years',
            'tuition_fee' => '¥28000/yr',
            'language_requirement' => 'IELTS 6.0',
            'min_cgpa' => 3.50,
            'application_deadline' => now()->addMonth(),
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('student.recommendations.index'));

        $response->assertOk();
        $this->assertDatabaseHas('recommendations', ['user_id' => $user->user_id]);
    }
}
