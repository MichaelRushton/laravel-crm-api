<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

if (! defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

pest()
    ->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');
