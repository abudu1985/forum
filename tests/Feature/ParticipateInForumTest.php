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

    public function test_unauthorized_user_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    function test_authorized_user_can_delete_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    function test_unauthorized_user_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    function test_authorized_user_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'You been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body' =>  $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }
}
