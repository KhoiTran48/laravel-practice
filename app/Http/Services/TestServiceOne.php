<?php

namespace App\Http\Services;


class TestServiceOne implements TestServiceInterface
{
    public function doSomething()
    {
        echo "TestServiceOne";
    }
}
