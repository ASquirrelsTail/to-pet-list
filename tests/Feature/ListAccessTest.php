<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\TList;
use App\Task;
use App\Share;

class ListAccessTest extends TestCase
{
    /**
     * Checks only a list author can access a list that is private.
     *
     * @return void
     */
    public function testOnlyAuthorCanViewPrivateList()
    {
        
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->get(route('lists.show', $list));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($other_user)->get(route('lists.show', $list));
        $response->assertStatus(401);

        $response = $this->actingAs($author)->get(route('lists.show', $list));
        $response->assertStatus(200);
    }

    /**
     * Checks anyone can view public lists.
     *
     * @return void
     */
    public function testAnyoneCanViewPublicList()
    {
        
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->states('public')->create(['user_id' => $author]);

        $response = $this->get(route('lists.show', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($other_user)->get(route('lists.show', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->get(route('lists.show', $list));
        $response->assertStatus(200);
    }

    /**
     * Checks only a list author, and users they have shared a list with can access a shared private list.
     *
     * @return void
     */
    public function testOnlyAuthorAndShareesCanViewPublicList()
    {
        
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->get(route('lists.show', $list));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($other_user)->get(route('lists.show', $list));
        $response->assertStatus(401);  //Unauthorised

        $response = $this->actingAs($sharee)->get(route('lists.show', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->get(route('lists.show', $list));
        $response->assertStatus(200);
    }
}
