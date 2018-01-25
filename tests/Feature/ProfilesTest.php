<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /**@test */
    public function test_it_a_user_has_a_profile()
    {
        $user = create('App\User');
        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    public function test_profiles_display_all_threads_created_by_that_user()
    {
        $user = create('App\User');
        $tread = create('App\Tread', ['user_id' => $user->id]);
        $this->get("/profiles/{$user->name}")
            ->assertSee($tread->title)
            ->assertSee($tread->body);
    }
}
