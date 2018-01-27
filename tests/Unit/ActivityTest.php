<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Activity;

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
}
