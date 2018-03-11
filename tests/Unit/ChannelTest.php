<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /**@test */
    public function test_a_channel_consist_of_treads()
    {
       $channel = create('App\Channel');
       $tread = create('App\Tread', ['channel_id' => $channel->id]);
       $this->assertTrue($channel->treads->contains($tread));
    }
}
