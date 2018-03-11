<?php

namespace Tests\Unit;

use App\Notifications\TreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    protected $tread;
    public function setUp()
    {
        parent::setUp();
        $this->tread = factory('App\Tread')->create();
    }
    public function test_a_user_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->tread->replies);
    }

    public function test_a_tread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->tread->creator);
    }

    public function test_a_tread_can_add_a_reply()
    {
        $this->tread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->tread->replies);
    }

    function test_tread_notifies_all_registered_subscribes_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->tread
            ->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => 999
        ]);

        Notification::assertSentTo(auth()->user(), TreadWasUpdated::class);
    }

    public function test_a_tread_belongs_to_a_channel()
    {
        $tread = create('App\Tread');

        $this->assertInstanceOf('App\Channel', $tread->channel);
    }

    public function test_a_tread_can_make_a_string_path()
    {
        $tread = create('App\Tread');
        //$this->assertEquals(url('/treads/') . '/' . $tread->channel->slug . '/' . $tread->id, $tread->path());
        $this->assertEquals( url('/treads/') . '/' . "{$tread->channel->slug}/{$tread->id}", $tread->path());
    }

    public function test_a_tread_can_be_subscribed_to()
    {
        $tread = create('App\Tread');

        //When user subscribe to the thread
        $tread->subscribe($userId = 1);

        // We should be able to fetch all treads that the user has subscribed to
        $this->assertEquals(
            1,
        $tread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    public function test_a_tread_can_be_unsubscribed_to()
    {
        $tread = create('App\Tread');

        //When user subscribe to the thread
        $tread->subscribe($userId = 1);

        $tread->unsubscribe($userId);

        // We should be able to fetch all treads that the user has subscribed to
        $this->assertCount(0, $tread->subscriptions);
    }

    public function test_it_knows_that_tread_is_subscribed_to()
    {
        $tread = create('App\Tread');

        $this->assertFalse($tread->isSubscribedTo);

        $this->signIn();

        //When user subscribe to the thread
        $tread->subscribe();

        $this->assertTrue($tread->isSubscribedTo);
    }

    public function test_a_tread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $tread = create('App\Tread');

        tap(auth()->user(), function ($user) use ($tread){

            $this->assertTrue($tread->hasUpdatesFor($user));

            $user->read($tread);

            $this->assertFalse($tread->hasUpdatesFor($user));
        });

    }
}
