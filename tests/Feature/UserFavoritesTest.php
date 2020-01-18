<?php

namespace Tests\Feature;

use App\Models\Favourite;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFavoritesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can add space to favourites.
     *
     * @return void
     */
    public function test_user_can_add_space_to_avourites()
    {
        $user  = factory(User::class)->create();
        $space = factory(Space::class)->create();

        $this->actingAs($user)->json('post', '/web/my-favourites', ['spaceId' => $space->id]);
        $this->assertDatabaseHas('favourites', ['space_id' => $space->id, 'user_id' => $user->id]);
    }

    /**
     * Test user can delete their own favourited space.
     */
    public function test_user_can_delete_own_favourite()
    {
        $favourite = factory(Favourite::class)->create();

        $this->assertDatabaseHas('favourites', ['space_id' => $favourite->space->id, 'user_id' => $favourite->user->id]);
        $this->actingAs($favourite->user)->json('delete', "/web/my-favourites/{$favourite->id}");
        $this->assertDatabaseMissing('favourites', ['space_id' => $favourite->space->id, 'user_id' => $favourite->user->id]);
    }

    /**
     * Test user can get a list of their favourites.
     */
    public function test_user_can_get_their_favourites()
    {
        $spaceCount = 5;
        $spaces     = factory(Space::class, $spaceCount)->create();
        $user       = factory(User::class)->create();
        $spaceIds   = $spaces->map(function ($item) {
            return ['space_id' => $item->id];
        });

        $user->favourites()->createMany($spaceIds->all());

        $response = $this->actingAs($user)->json('get', "/web/my-favourites");
        $content  = json_decode($response->getContent(), true);

        $this->assertEquals(count($content['favourites']), $spaceCount);
    }

    /**
     * Test user cannot delete favourites that doesn't belong to them.
     */
    public function test_user_cannot_delete_other_users_favourite()
    {
        $favourite = factory(Favourite::class)->create();
        $user      = factory(User::class)->create();
        $response  = $this->actingAs($user)
                          ->json('delete', "/web/my-favourites/{$favourite->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('favourites', ['id' => $favourite->id]);
    }
}
