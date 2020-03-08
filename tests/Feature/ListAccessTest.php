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
    use RefreshDatabase;
    /**
     * Checks only a registered user can view their lists.
     *
     * @return void
     */
    public function testOnlyRegisteredUserCanViewLists()
    {
        
        $user = factory(User::class)->create();

        $response = $this->get(route('lists.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('lists.index'));
        $response->assertStatus(200);
    }

    /**
     * Checks only a registered user can create a list.
     *
     * @return void
     */
    public function testOnlyRegisteredUserCanCreateList()
    {
        
        $user = factory(User::class)->create();

        $response = $this->get(route('lists.create'));
        $response->assertStatus(403);

        $response = $this->post(route('lists.store'), ['name'=>'Guest list']);
        $this->assertDatabaseMissing('lists', ['name'=>'Guest list']);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('lists.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(route('lists.store'), ['name'=>'Test list']);
        $this->assertDatabaseHas('lists', ['name'=>'Test list']);

        $list = TList::where('name', 'Test list')->first();
        $response->assertRedirect(route('lists.show', $list));
    }

    /**
     * Checks only a list's author can edit a list.
     *
     * @return void
     */
    public function testOnlyAuthorCanEditList()
    {
        
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->get(route('lists.edit', $list));
        $response->assertStatus(403);

        $response = $this->post(route('lists.update', $list), ['name'=>'Guest updated list', '_method'=>'PATCH']);
        $this->assertNotEquals($list->refresh()->name, 'Guest updated list');
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->get(route('lists.edit', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->post(route('lists.update', $list),
            ['name'=>'Another updated list', '_method'=>'PATCH']);
        $this->assertNotEquals($list->refresh()->name, 'Another updated list');
        $response->assertStatus(403);

        $response = $this->actingAs($author)->get(route('lists.edit', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->post(route('lists.update', $list),
            ['name'=>'Test updated list', '_method'=>'PATCH']);
        $this->assertEquals($list->refresh()->name, 'Test updated list');
        $response->assertRedirect(route('lists.show', $list));
    }

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
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->get(route('lists.show', $list));
        $response->assertStatus(403);

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
    public function testOnlyAuthorAndShareesCanViewSharedList()
    {
        
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->get(route('lists.show', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->get(route('lists.show', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($sharee)->get(route('lists.show', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->get(route('lists.show', $list));
        $response->assertStatus(200);
    }
}
