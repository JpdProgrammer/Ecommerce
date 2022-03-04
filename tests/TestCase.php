<?php

namespace Tests;

use Carbon\Traits\Test;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\TestHelper;
use Tests\CreateData;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreateData;
}
