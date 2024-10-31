<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public const API_ENDPOINT = '/api/1/nyt/best-sellers';
}
