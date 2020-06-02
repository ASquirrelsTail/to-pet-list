<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Session;
use App\TList;
use App\Task;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Task::class, 'task');
    }

    protected function checkTaskOnList(TList $list, Task $task)
    {
        if ($task->list != $list) {
            abort(404);
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TList $list)
    {
        return redirect(route('lists.show', $list));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TList $list)
    {
        return view('create-task', ['list'=>$list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TList $list)
    {
        $request->validate(['name'=>'required|max:100']);

        $task = new Task;
        $task->user()->associate(Auth::user());
        $task->list()->associate($list);
        $task->name = $request->input('name');
        $task->completed = $request->has('completed');
        if ($request->has('priority')) {
            $task->new_position = 0;
            $task->priority = true;
        }
        $task->save();

        if ($request->isJson()) {
            return $task;
        }
        Session::flash('status', 'Successfully created task.');

        return redirect(route('lists.show', $list) . '#task-' . $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TList $list, Task $task)
    {
        return redirect(route('lists.show', $task->list) . '#task-' . $task->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TList $list, Task $task)
    {
        return view('edit-task', ['list'=>$list, 'task'=>$task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TList $list, Task $task)
    {
        $request->validate(['name'=>'required|max:100', 'new_position'=>'integer|min:0']);

        $task->fill($request->all());
        $task->completed = $request->has('completed');
        if ($request->has('priority')) {
            if (!$task->priority) $task->new_position = 0;
            $task->priority = true;
        } else $task->priority = false;
        $task->save();

        if ($request->isJson()) return response()->noContent();

        Session::flash('status', 'Successfully updated task.');

        return $this->show($list, $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TList $list, Task $task)
    {
        $task->delete();

        if ($request->isJson()) return response()->noContent();

        Session::flash('status', 'Successfully deleted task.');

        return redirect(route('lists.show', $list));
    }

    public function completed(Request $request, TList $list, Task $task)
    {
        $this->authorize('complete', $task);
        $task->completed = true;
        $task->save();

        if ($request->isJson()) return response()->noContent();
        return $this->show($list, $task);
    }
}
