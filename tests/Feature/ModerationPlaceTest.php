<?php

namespace Tests\Feature;

use Tests\TestCase;
use Solutionlocale\Commons\Models\User;
use Solutionlocale\Commons\Models\Place;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModerationPlaceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_moderator_can_open_a_place()
    {
        $user = factory(User::class)->create([
            'is_admin'  =>  1
        ]);
        $places = factory(Place::class, 5)->create([
            'is_closed' => 1
        ]);

        $open_places = Place::opened()->get()->count();

        $this->assertEquals(0, $open_places);

        $this->actingAs($user)->post(route('moderation.closing', $places->first()->slug), [
            'is_closed' => 0
        ]);

        $open_places = Place::opened()->get()->count();
        $this->assertEquals(1, $open_places);
    }
}
