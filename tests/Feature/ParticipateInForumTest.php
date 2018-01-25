<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ParticipateInForum extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function test_unautenticated_users_may_not_add_replies()
    {
       // $this->expectException('Illuminate\Auth\AuthenticationException');

//        $tread = factory('App\Tread')->create();
//        $reply = factory('App\Reply')->create();
//        $this->post( $tread->path() . '/replies', $reply->toArray());

        // instead of this
        $this->withExceptionHandling()
            ->post('/treads/some-channel/1/replies', [])
            ->assertRedirect('/login');

    }

    public function test_an_authenticated_user_may_participate_in_forum_tread()
    {
        // We have authenticated user
        //$user = factory('App\User')->create();  // not authenticated user
        $this->be($user = create('App\User'));

        // And an existing thread
        $tread = create('App\Tread');

        // When the user adds a reply to the thread
        $reply = make('App\Reply');
        $this->post($tread->path() . '/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($tread->path())
            ->assertSee($reply->body);

    }

    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $tread = create('App\Tread');
        $reply = make('App\Reply', ['body' => null]);

        return $this->post($tread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
