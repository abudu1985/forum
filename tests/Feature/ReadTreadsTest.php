<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadTreadsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $tread;

    public function setUp()
    {
        parent::setUp();

        $this->tread = factory('App\Tread')->create();
    }

    /**@test*/
    public function test_a_user_can_see_all_treads()
    {
        $response = $this->get('/treads')
            ->assertSee($this->tread->title);
    }

    /**@test*/
    public function test_a_user_can_see_single_tread()
    {
        $response = $this->get( $this->tread->path())
            ->assertSee($this->tread->title);
    }

//    function test_a_user_can_read_replies_related_to_thread()
//    {
//      $reply = factory('App\Reply')
//          ->create(['tread_id' => $this->tread->id]);
//
//      $this->get( $this->tread->path())
//      ->assertSee($reply->body);
//    }

    public function test_a_user_can_filter_treads_acording_to_channel()
    {
        $channel = create('App\Channel');
        $treadInChannel = create('App\Tread', ['channel_id' => $channel->id]);
        $treadNotInChannel = create('App\Tread');

        $this->get('/treads/' . $channel->slug)
            ->assertSee($treadInChannel->title)
            ->assertDontSee($treadNotInChannel->title);
    }

    public function test_a_user_can_filter_treads_by_any_username()
    {
       $this->signIn(create('App\User', ['name' => 'JohnDoe']));
       $treadByJohn = create('App\Tread', ['user_id' => auth()->id()]);
       $treadNotByJohn = create('App\Tread');

       $this->get('treads?by=JohnDoe')
           ->assertSee($treadByJohn->title)
           ->assertDontSee($treadNotByJohn->title);
    }

    public function test_a_user_can_filter_treads_by_popularity()
    {

        $treadWithTwoReplies = create('App\Tread');
        create('App\Reply', ['tread_id' => $treadWithTwoReplies->id], 2);

        $treadWithThreeReplies = create('App\Tread');
        create('App\Reply', ['tread_id' => $treadWithThreeReplies->id], 3);

        $treadWithNoReplies = $this->tread;


        $response = $this->getJson('treads?popular=1')->json();
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    public function test_a_user_can_filter_treads__by_those_that_are_unanswered()
    {
        $tread= create('App\Tread');
        create('App\Reply', ['tread_id' => $tread->id]);
        $response = $this->getJson('treads?unanswered=1')->json();
        $this->assertCount(1, $response);

    }

    public function test_user_can_request_all_replies_for_a_given_thread()
    {
        $tread= create('App\Tread');
        create('App\Reply', ['tread_id' => $tread->id], 2);

        $response = $this->getJson($tread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
