<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function sanctum(User $user): static
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer '.$user->createToken('auth')->plainTextToken,
        ]);
    }
}
