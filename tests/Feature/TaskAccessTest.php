<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\TList;
use App\Task;
use App\Share;

class TaskAccessTest extends TestCase
{
    use RefreshDatabase;

    // Test adding Tasks
    public function testListAuthorCanAddTask()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->actingAs($author)->get(route('tasks.create', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->post(route('tasks.store', $list), ['name'=>'New Task']);
        $response->assertStatus(302);

        $this->assertDatabaseHas('tasks', ['name'=>'New Task', 'list_id'=>$list->id, 'user_id'=>$author->id]);
    }

    public function testListShareeWithPermissionCanAddTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        factory(Share::class)->states('create')->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->get(route('tasks.create', $list));
        $response->assertStatus(200);

        $response = $this->actingAs($sharee)->post(route('tasks.store', $list), ['name'=>'New Task']);
        $response->assertStatus(302);

        $this->assertDatabaseHas('tasks', ['name'=>'New Task', 'list_id'=>$list->id, 'user_id'=>$sharee->id]);
    }

    public function testRandomUserCantAddTask()
    {
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        $response = $this->actingAs($other_user)->get(route('tasks.create', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->post(route('tasks.store', $list), ['name'=>'New Task']);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['name'=>'New Task', 'list_id'=>$list->id]);
    }

    public function testListShareeWithoutPermissionCantAddTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->get(route('tasks.create', $list));
        $response->assertStatus(403);

        $response = $this->actingAs($sharee)->post(route('tasks.store', $list), ['name'=>'New Task']);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['name'=>'New Task', 'list_id'=>$list->id, 'user_id'=>$sharee->id]);
    }

    // Test Editing Tasks
    public function testListAuthorCanEditTask()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($author)->get(route('tasks.edit', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(200);

        $response = $this->actingAs($author)->post(route('tasks.update', ['list'=>$list, 'task'=>$task]), ['name'=>'Updated Task', '_method'=>'PATCH']);
        $response->assertStatus(302);

        $this->assertEquals($task->refresh()->name, 'Updated Task');
    }

    public function testListShareeWithPermissionCanEditTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->states('update')->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->get(route('tasks.edit', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(200);

        $response = $this->actingAs($sharee)->post(route('tasks.update', ['list'=>$list, 'task'=>$task]), ['name'=>'Updated Task', '_method'=>'PATCH']);
        $response->assertStatus(302);

        $this->assertEquals($task->refresh()->name, 'Updated Task');
    }

    public function testRandomUserCantEditTask()
    {
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->get(route('tasks.edit', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(403);

        $response = $this->actingAs($other_user)->post(route('tasks.update', ['list'=>$list, 'task'=>$task]), ['name'=>'Updated Task', '_method'=>'PATCH']);
        $response->assertStatus(403);

        $this->assertNotEquals($task->refresh()->name, 'Updated Task');
    }

    public function testListShareeWithoutPermissionCantEditTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->get(route('tasks.edit', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(403);

        $response = $this->actingAs($sharee)->post(route('tasks.update', ['list'=>$list, 'task'=>$task]), ['name'=>'Updated Task', '_method'=>'PATCH']);
        $response->assertStatus(403);

        $this->assertNotEquals($task->refresh()->name, 'Updated Task');
    }

    //Test Delete task.
    public function testListAuthorCanDeleteTask()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($author)->post(route('tasks.destroy', ['list'=>$list, 'task'=>$task]), ['_method'=>'DELETE']);
        $response->assertStatus(302);

        $this->assertDeleted('tasks', ['id'=>$task->id]);
    }

    public function testListShareeWithPermissionCanDeleteTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->states('delete')->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->post(route('tasks.destroy', ['list'=>$list, 'task'=>$task]), ['_method'=>'DELETE']);
        $response->assertStatus(302);

        $this->assertDeleted('tasks', ['id'=>$task->id]);
    }

    public function testRandomUserCantDeleteTask()
    {
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->post(route('tasks.destroy', ['list'=>$list, 'task'=>$task]), ['_method'=>'DELETE']);
        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['id'=>$task->id]);
    }

    public function testListShareeWithoutPermissionCantDeleteTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->post(route('tasks.destroy', ['list'=>$list, 'task'=>$task]), ['_method'=>'DELETE']);
        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['id'=>$task->id]);
    }

    // Test Completing Tasks
    public function testListAuthorCanCompleteTask()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($author)->post(route('tasks.completed', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(302);

        $this->assertEquals($task->refresh()->completed, true);
    }

    public function testListShareeWithPermissionCanCompleteTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->states('complete')->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->post(route('tasks.completed', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(302);

        $this->assertEquals($task->refresh()->completed, true);
    }

    public function testRandomUserCantCompleteTask()
    {
        $author = factory(User::class)->create();
        $other_user = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($other_user)->post(route('tasks.completed', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(403);

        $this->assertEquals($task->refresh()->completed, false);
    }

    public function testListShareeWithoutPermissionCantCompleteTask()
    {
        $author = factory(User::class)->create();
        $sharee = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        factory(Share::class)->create(['email' => $sharee->email, 'list_id' => $list]);

        $response = $this->actingAs($sharee)->post(route('tasks.completed', ['list'=>$list, 'task'=>$task]));
        $response->assertStatus(403);

        $this->assertEquals($task->refresh()->completed, false);
    }

    public function testTaskNestedInWrongListResourceReturns404()
    {
        $author = factory(User::class)->create();

        $list = factory(TList::class)->create(['user_id' => $author]);
        $wrong_list = factory(TList::class)->create(['user_id' => $author]);

        $task = factory(Task::class)->create(['user_id' => $author, 'list_id' => $list]);

        $response = $this->actingAs($author)->get(route('tasks.show', ['list'=>$wrong_list, 'task'=>$task]));
        $response->assertStatus(404);

        $response = $this->actingAs($author)->get(route('tasks.edit', ['list'=>$wrong_list, 'task'=>$task]));
        $response->assertStatus(404);

        $response = $this->actingAs($author)->post(route('tasks.update', ['list'=>$wrong_list, 'task'=>$task]), ['name'=>'Updated Task', '_method'=>'PATCH']);
        $response->assertStatus(404);

        $this->assertNotEquals($task->refresh()->name, 'Updated Task');

        $response = $this->actingAs($author)->post(route('tasks.completed', ['list'=>$wrong_list, 'task'=>$task]));
        $response->assertStatus(404);

        $this->assertEquals($task->refresh()->completed, false);

        $response = $this->actingAs($author)->post(route('tasks.destroy', ['list'=>$wrong_list, 'task'=>$task]), ['_method'=>'DELETE']);
        $response->assertStatus(404);

        $this->assertDatabaseHas('tasks', ['id'=>$task->id]);
    }

}
