<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Activity;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /**@test */
    public function test_it_records_activity_when_a_tread_is_created()
    {
        $this->signIn();
        $tread = create('App\Tread');
        $this->assertDatabaseHas('activities',[
            'type' => 'created_tread',
            'user_id' => auth()->id(),
            'subject_id' => $tread->id,
            'subject_type' => 'App\Tread'
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $tread->id);
    }

    public function test_it_records_activity_when_reply_is_created()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $this->assertEquals(2, Activity::count());
    }

    public function test_it_fetches_a_feed_for_any_user()
    {
        // have a thread
        $this->signIn();
        create('App\Tread', ['user_id' => auth()->id()], 2);

//        // and another thread from a week ago
//           create('App\Tread', [
//              'user_id' => auth()->id(),
//               'created_at' => Carbon::now()->subWeek()
//           ]);

           auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

           // when we fetch their feed
           $feed = Activity::feed(auth()->user());

           // Then, it should be returned in the proper format
            $this->assertTrue($feed->keys()->contains(
                Carbon::now()->format('Y-m-d')
            ));

            $this->assertTrue($feed->keys()->contains(
                Carbon::now()->subWeek()->format('Y-m-d')
            ));
    }
}
