<?php

namespace Tests;

use Carbon\Traits\Test;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\TestHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use TestHelper;
}
