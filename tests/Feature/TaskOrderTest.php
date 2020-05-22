<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\User;
use App\TList;
use App\Task;

class TaskOrderTest extends TestCase
{
    use RefreshDatabase;

    public function createList($user, $no_tasks)
    {
        $list = factory(TList::class)->create(['user_id' => $user]);
        for ($i = 0; $i < $no_tasks; $i++) {
            factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        }

        return $list;
    }

    public function testNewTasksAddedToBottomOfList()
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $new_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $this->assertEquals($new_task->id, $list->tasks()->orderBy('position', 'desc')->first()->id);
    }

    public function testNewPositionCantBeLessThan0()
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $response = $this->actingAs($user)
                         ->post(route('tasks.update', 
                                      ['list'=>$list, 'task'=>$task]),
                                      ['name'=>'Moved Task', 'new_position'=> '-10', '_method'=>'PATCH']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['new_position'=>'The new position must be at least 0.']);

        $this->assertEquals($task->id, $list->tasks()->orderBy('position', 'desc')->first()->id);
    }

    public function testSettingTasksNewPositionToZeroMovesItToTopOfList()
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $response = $this->actingAs($user)
                         ->post(route('tasks.update', 
                                      ['list'=>$list, 'task'=>$task]),
                                      ['name'=>'Moved Task', 'new_position'=> '0', '_method'=>'PATCH']);
        $response->assertStatus(302);

        $this->assertEquals($task->id, $list->tasks()->orderBy('position', 'asc')->first()->id);
    }

    public function testSettingTasksNewPositionMovesItToThatPosition()
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $response = $this->actingAs($user)
                         ->post(route('tasks.update', 
                                      ['list'=>$list, 'task'=>$task]),
                                      ['name'=>'Moved Task', 'new_position'=> '5', '_method'=>'PATCH']);
        $response->assertStatus(302);

        $this->assertEquals($task->id, $list->tasks()->orderBy('position', 'asc')->get()[5]->id);
    }

    public function testSettingTasksNewPositionOutsideRangeMovesItToTheBottomOfList()
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $this->actingAs($user)
             ->post(route('tasks.update', 
                          ['list'=>$list, 'task'=>$task]),
                          ['name'=>'Moved Task', 'new_position'=> '5', '_method'=>'PATCH']);

        $this->actingAs($user)
             ->post(route('tasks.update', 
                          ['list'=>$list, 'task'=>$task]),
                          ['name'=>'Moved Task', 'new_position'=> '25', '_method'=>'PATCH']);

        $this->assertEquals($task->id, $list->tasks()->orderBy('position', 'desc')->first()->id);
    }

    public function testRepeatedlySwappingToTheSamePositionDoesntCauseProblems()
    // Halving floats for position eventually runs out of precision causing the task 
    // to have the same position values as adjacent_task, and the tasks will return to 
    // default id ordering with adjacent task in positon 5, when it should be in 4.
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $next_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        $task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        $previous_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $this->actingAs($user)
             ->post(route('tasks.update', 
                          ['list'=>$list, 'task'=>$previous_task]),
                          ['name'=>'Adjacent Task', 'new_position'=> '4', '_method'=>'PATCH']);

        $this->actingAs($user)
             ->post(route('tasks.update', 
                          ['list'=>$list, 'task'=>$next_task]),
                          ['name'=>'Adjacent Task', 'new_position'=> '5', '_method'=>'PATCH']);

        for($i = 0; $i < 20; $i++) {
            $this->actingAs($user)
                 ->post(route('tasks.update', 
                              ['list'=>$list, 'task'=>$task]),
                              ['name'=>'Moved Task', 'new_position'=> '5', '_method'=>'PATCH']);

            $this->assertEquals($task->id, $list->tasks()->orderBy('position', 'asc')->get()[5]->id);
        }
    }

    public function testRepeatedlySwappingThePositionOfTwoTasksDoesntCauseProblems()
    // Halving floats for position eventually runs out of precision causing the task 
    // to have the same position values as adjacent_task, and the tasks will return to 
    // default id ordering with adjacent task in positon 5, when it should be in 4.
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $first_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        $second_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        $adjacent_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        $this->actingAs($user)
             ->post(route('tasks.update', 
                          ['list'=>$list, 'task'=>$adjacent_task]),
                          ['name'=>'Adjacent Task', 'new_position'=> '4', '_method'=>'PATCH']);

        for($i = 0; $i < 20; $i++) {
            $this->actingAs($user)
                 ->post(route('tasks.update', 
                              ['list'=>$list, 'task'=>$second_task]),
                              ['name'=>'Second Task', 'new_position'=> '5', '_method'=>'PATCH']);

            $this->actingAs($user)
                 ->post(route('tasks.update', 
                              ['list'=>$list, 'task'=>$first_task]),
                              ['name'=>'First Task', 'new_position'=> '5', '_method'=>'PATCH']);

            $this->assertEquals($first_task->id, $list->tasks()->orderBy('position', 'asc')->get()[5]->id);
        }
    }

    public function testRepeatedlySwappingThePositionOfTwoTasksToPosition0DoesntCauseProblems()
    // Halving floats for position eventually runs out of precision causing the task 
    // to have the same position values as the next task, and the tasks will return to 
    // default id ordering with adjacent task in positon 1, when it should be in 0.
    {
        $user = factory(User::class)->create();
        $list = $this->createList($user, 10);

        $second_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);
        $first_task = factory(Task::class)->create(['user_id' => $user, 'list_id' => $list]);

        for($i = 0; $i < 20; $i++) {
            $this->actingAs($user)
                 ->post(route('tasks.update', 
                              ['list'=>$list, 'task'=>$second_task]),
                              ['name'=>'Second Task', 'new_position'=> '0', '_method'=>'PATCH']);

            $this->actingAs($user)
                 ->post(route('tasks.update', 
                              ['list'=>$list, 'task'=>$first_task]),
                              ['name'=>'First Task', 'new_position'=> '0', '_method'=>'PATCH']);

            $this->assertEquals($first_task->id, $list->tasks()->orderBy('position', 'asc')->get()[0]->id);
        }
    }
}
