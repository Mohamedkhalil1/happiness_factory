<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_livewire_profile_component_on_profile_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get('/profile')
            ->assertSuccessful()
            ->assertSeeLivewire('profile.show');

    }
}
