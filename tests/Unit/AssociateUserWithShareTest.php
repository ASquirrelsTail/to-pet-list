<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\TList;
use App\Share;

class AssociateUserWithShareTest extends TestCase
{
		use RefreshDatabase;

    // Check that existing users are associated with shares when their email is used.
    public function testShareAssociatesExistingUserWithEmail()
    {
        $user = factory(User::class)->create();

        $list = factory(TList::class)->create();
        $share = factory(Share::class)->create(['email' => $user->email, 'list_id' => $list]);

        $this->assertEquals($share->refresh()->user_id, $user->id);
    }

    // Check that new and updated users with shares by their email.
    public function testUserAssociatesEmailWithExistingShare()
    {
    		$list = factory(TList::class)->create();
    		$share_new = factory(Share::class)->create(['email' => 'test@test.com', 'list_id' => $list]);
    		
    		$this->assertNull($share_new->refresh()->user_id);

    		$new_user = factory(User::class)->create(['email'=>'test@test.com']);

    		$this->assertEquals($share_new->refresh()->user_id, $new_user->id);

    		$share_updated = factory(Share::class)->create(['email' => 'updated@test.com', 'list_id' => $list]);
    		$updated_user = factory(User::class)->create();

    		$this->assertNull($share_updated->refresh()->user_id);

    		$updated_user->email = 'updated@test.com';
    		$updated_user->save();

    		$this->assertEquals($share_updated->refresh()->user_id, $updated_user->id);
    }
}
