<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /**@test */
    public function test_guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /**@test */
    public function test_a_authenticated_user_can_favorit_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');  // also create the thread

        // if iI post to a "favorite" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        //dd(\App\Favorite::all());

        // it should be recorded in the database
        $this->assertCount(1, $reply->favorites);
    }

    /**@test */
    public function test_a_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');  // also create the thread

        // if iI post to a "favorite" endpoint
        // $this->post('replies/' . $reply->id . '/favorites');
        $reply->favorite();

        // it should be recorded in the database
        // $this->assertCount(1, $reply->favorites);

        // after send delete request
        $this->delete('replies/' . $reply->id . '/favorites');

        // then it should be unrecorded in the database
        $this->assertCount(0, $reply->fresh()->favorites);
    }

    public function test_an_authenticated_user_may_only_favorite_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert the same record set twice.');
        }
        $this->assertCount(1, $reply->favorites);
    }
}

