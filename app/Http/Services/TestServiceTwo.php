<?php

namespace App\Http\Services;


class TestServiceTwo implements TestServiceInterface
{
    public function doSomething()
    {
        echo "TestServiceTwo";
    }
}
