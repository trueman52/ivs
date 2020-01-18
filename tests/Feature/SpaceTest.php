<?php

namespace Tests\Feature;

use App\Enums\SpaceStatus;
use App\Models\Space;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpaceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see if we can retrieve active space details via api.
     *
     * @return void
     */
    public function test_can_retrieve_active_space_details()
    {
        $space     = factory(Space::class)->create();
        $response  = $this->json('get', "web/spaces/{$space->id}");
        $retrieved = json_decode($response->getContent(), true)['space'];

        $this->assertEquals($space->id, $retrieved['id']);
    }

    /**
     * Test to see if we can retrieve active spaces via api.
     *
     * @return void
     */
    public function test_can_retrieve_active_spaces()
    {
        $count  = rand(3, 5);
        $spaces = factory(Space::class, $count)->create();

        // Set one space to inactive. Minus 1 from $count to ensure
        // we are only getting active spaces.
        $inactive         = $spaces->first();
        $inactive->status = SpaceStatus::UNPUBLISHED();

        $inactive->save();

        $response  = $this->json('get', 'web/spaces');
        $retrieved = json_decode($response->getContent(), true)['spaces']['data'];

        $this->assertEquals(count($retrieved), $count - 1);
    }

    /**
     * Test to see if we can retrieve active space details via api.
     *
     * @return void
     */
    public function test_cannot_retrieve_inactive_space_details()
    {
        $space         = factory(Space::class)->create();
        $space->status = SpaceStatus::UNPUBLISHED();

        $space->save();

        $response = $this->json('get', "web/spaces/{$space->id}");

        $response->assertStatus(404);
    }
}
