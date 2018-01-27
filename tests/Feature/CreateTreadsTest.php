<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class CreateTreadsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_guests_may_not_create_treads()
    {
//        $this->expectException('Illuminate\Auth\AuthenticationException');
//        $tread = make('App\Tread');
//        $this->post('/treads', $tread->toArray());

        // instead of above code and not to create and see also
        $this->withExceptionHandling();

            $this->get('/treads/create')
            ->assertRedirect('/login');

            $this->post('/treads')
            ->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_create_new_forum_treads()
    {
        // video 9,10
        // we have authenticated user
        $this->signIn();

        // when we hit endpoint to create a new tread
        $tread = create('App\Tread');
//       echo($tread->path());
//        exit();
//        var_dump($tread->toArray());
//        exit();
     //  $r = $this->post('/treads', $tread->toArray());
    //   dd($r->headers->get('Location'));
//exit();
        //Then, when we visit tread page
         $this->get($tread->path())

        // We should see the new tread
             ->assertSee($tread->title)
            ->assertSee($tread->body);
    }

    public function test_a_tread_reques_a_title()
    {
       $this->publishTread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_tread_reques_a_body()
    {
        $this->publishTread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_tread_reques_a_valid_channel_id()
    {
        factory('App\Channel', 2)->create();

        $this->publishTread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishTread(['channel_id' => 9999])
            ->assertSessionHasErrors('channel_id');
    }

    public function test_unauthorized_user_maynot_delete_treads()
    {
        $this->withExceptionHandling();
        $tread = create('App\Tread');
        $this->delete($tread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($tread->path())->assertStatus(403);
    }

    public function test_authorized_user_can_deleted_a_treads()
    {
        $this->signIn();
        $tread = create('App\Tread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['tread_id' => $tread->id]);
        $response = $this->json('DELETE', $tread->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('treads', ['id' => $tread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function publishTread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $tread = make('App\Tread', $overrides);

       return $this->post('/treads', $tread->toArray());
    }



}
