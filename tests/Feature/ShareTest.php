<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\TList;
use App\Task;
use App\Share;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function testCantShareListWithSelf()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->actingAs($author)->post(route('shares.store', $list), ['email'=>$author->email]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('shares', ['email'=>$author->email]);
    }

    public function testCantShareListWithSameEmailTwice()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        factory(Share::class)->create(['email' => 'test@test.com', 'list_id' => $list]);

        $response = $this->actingAs($author)->post(route('shares.store', $list), ['email'=>'test@test.com']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

        $this->assertEquals(Share::where('email', 'test@test.com')->count(), 1);
    }

    public function testOnlyListAuthorCanCreateShares()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->get(route('shares.create', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->post(route('shares.store', $list), ['email'=>'test@test.com']);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('shares', ['email'=>'test@test.com']);

        $response = $this->actingAs($sharee)->get(route('shares.create', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($sharee)->post(route('shares.store', $list), ['email'=>'test@test.com']);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('shares', ['email'=>'test@test.com']);

        $response = $this->actingAs($author)->get(route('shares.create', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->post(route('shares.store', $list), ['email'=>'test@test.com']);
        $response->assertStatus(302);

        $this->assertDatabaseHas('shares', ['email'=>'test@test.com']);
    }

    public function testOnlyListAuthorCanEditShares()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $share = factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->get(route('shares.edit', ['list'=>$list, 'share'=>$share]));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['complete'=>true, '_method'=>'PATCH']);
        $response->assertStatus(403);

        $this->assertFalse($share->refresh()->complete);

        $response = $this->actingAs($sharee)->get(route('shares.edit', ['list'=>$list, 'share'=>$share]));
        $response->assertStatus(403);

        $response = $this->actingAs($sharee)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['complete'=>true, '_method'=>'PATCH']);
        $response->assertStatus(403);

        $this->assertFalse($share->refresh()->complete);

        $response = $this->actingAs($author)->get(route('shares.edit', ['list'=>$list, 'share'=>$share]));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['complete'=>true, '_method'=>'PATCH']);
        $response->assertStatus(302);

        $this->assertTrue($share->refresh()->complete);
    }

    public function testOnlyListAuthorCanDeleteShares()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $share = factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['_method'=>'DELETE']);
        $response->assertStatus(403);

        $this->assertDatabaseHas('shares', ['email'=>$sharee->email]);

        $response = $this->actingAs($sharee)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['_method'=>'DELETE']);
        $response->assertStatus(403);

        $this->assertDatabaseHas('shares', ['email'=>$sharee->email]);

        $response = $this->actingAs($author)->post(route('shares.update', ['list'=>$list, 'share'=>$share]), ['_method'=>'DELETE']);
        $response->assertStatus(302);

        $this->assertDatabaseMissing('shares', ['email'=>$sharee->email]);
    }
}
