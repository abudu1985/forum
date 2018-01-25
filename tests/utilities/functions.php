<?php
/**
 * Created by PhpStorm.
 * User: Anna
 * Date: 14.01.2018
 * Time: 9:50
 */

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}