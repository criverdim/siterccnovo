<?php

namespace Tests\Feature;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupColorFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_group_color_accessor_formats_hex(): void
    {
        $g = Group::create([
            'name' => 'Grupo Teste',
            'weekday' => 'monday',
            'time' => now(),
            'address' => 'Rua X',
            'color_hex' => '0b7a48',
        ]);

        $this->assertSame('#0b7a48', $g->color_hex);
    }

    public function test_color_map_is_cached_and_invalidated(): void
    {
        $g = Group::create([
            'name' => 'Grupo A',
            'weekday' => 'monday',
            'time' => now(),
            'address' => 'Rua X',
            'color_hex' => '#c9a043',
        ]);

        $map = Group::colorMap();
        $this->assertArrayHasKey($g->id, $map);
        $this->assertSame('#c9a043', $map[$g->id]);

        $g->update(['color_hex' => '#0b7a48']);
        $map2 = Group::colorMap();
        $this->assertSame('#0b7a48', $map2[$g->id]);
    }
}

