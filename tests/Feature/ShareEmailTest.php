<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\TList;
use App\Share;
use App\Mail\ListShared;

class ShareEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testSharingListEmailsNonUser()
    {
        Mail::fake();

        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->actingAs($author)->post(route('shares.store', $list), ['email'=>'test@test.com']);
        $response->assertStatus(302);

        Mail::assertSent(ListShared::class, function ($mail) {
            return $mail->hasTo('test@test.com');
        });
    }

    public function testSharingListEmailsExistingUser()
    {
        Mail::fake();

        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->actingAs($author)->post(route('shares.store', $list), ['email'=>$sharee->email]);
        $response->assertStatus(302);

        Mail::assertSent(ListShared::class, function ($mail) use ($sharee) {
            return $mail->hasTo($sharee->email);
        });
    }
}
