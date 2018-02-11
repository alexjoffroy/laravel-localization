<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use AlexJoffroy\LaravelLocalization\Providers\LocalizationServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [LocalizationServiceProvider::class];
    }
}
