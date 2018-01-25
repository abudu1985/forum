<?php

namespace Tests\Unit;

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
}
