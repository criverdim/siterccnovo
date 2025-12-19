<?php

namespace Tests\Unit;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupColorTest extends TestCase
{
    use RefreshDatabase;

    public function test_color_accessor_prefixes_hash(): void
    {
        $g = Group::create([
            'name' => 'G', 'weekday' => 'monday', 'time' => now(), 'address' => 'Rua'
        ]);
        $g->color_hex = 'c9a043';
        $g->save();
        $this->assertSame('#c9a043', $g->color_hex);
    }
}

