<?php
/**
 * Created by PhpStorm.
 * User: Anna
 * Date: 16.02.2018
 * Time: 17:31
 */

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class SubscribeToTreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**@test */
    public function test_a_user_can_subscribe_to_treads()
    {
        $this->signIn();
        $tread = create('App\Tread');
        $this->post($tread->path() . '/subscriptions');

        $this->assertCount(1, $tread->fresh()->subscriptions);
    }

    public function test_a_user_can_unsubscribe_from_treads()
    {
        $this->signIn();
        $tread = create('App\Tread');

        $tread->subscribe();

        $this->delete($tread->path() . '/subscriptions');

        $this->assertCount(0, $tread->subscriptions);



    }
}
