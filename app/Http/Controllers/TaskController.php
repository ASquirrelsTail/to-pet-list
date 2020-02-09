<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Log;
use Illuminate\Http\Request;

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
    }

    /**
     * Checks a task exists and belongs to the current user.
     * Returns a collection for that task.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function getTask($id)
    {
        if (is_numeric($id)) {
            $id = (int) $id;
        } else {
            abort(404);
        }

        $user_id = Auth::user()->id;
        $task_query = DB::table('tasks')->where('id', $id);
        $task_user_id = $task_query->pluck('user_id')->first();

        if(!$task_user_id) {
            abort(404);
        } elseif ($task_user_id != $user_id) {
            abort(403);
        } else {
            return $task_query;
        }
    }

    /**
     * Show the task list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user_name = $user->name;

        $tasks = DB::table('tasks')->where('user_id', $user_id)->get();

        return view('home', ['name' => $user_name, 'tasks' => $tasks, 'error' => false]);
    }

    /**
     * Show an individual task.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function task($id)
    {
        $task = $this->getTask($id)->first();
        return view('task', ['task' => $task, 'error' => false]);
    }

    /**
     * Creates a task for the current user. Returns the index with any errors if relevant.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addTask(Request $request)
    {
        $task_name = $request->input('task_name');
        $task_name = trim($task_name);

        $error = false;

        if (strlen($task_name) < 1) {
            $error = 'Please input a value!';
        } elseif (strlen($task_name) > 100) {
            $error = 'Please input a value shorter than 100 chars.';
        } else {
            $user_id = Auth::user()->id;

            DB::insert('INSERT INTO tasks (user_id, task_name) VALUES (?, ?)', [$user_id, $task_name]);
            Session::flash('status', 'Successfully created task.');
        }

        return $this->index()->with('error', $error);
    }

    /**
     * Edits a given task if it exists and belongs to the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editTask(Request $request, $id)
    {
        $task_name = $request->input('task_name');
        $task_name = trim($task_name);

        $completed = (bool) $request->input('completed');

        $error = false;

        if (strlen($task_name) < 1) {
            $error = 'Please input a value!';
        } elseif (strlen($task_name) > 100) {
            $error = 'Please input a value shorter than 100 chars.';
        } else {
            $this->getTask($id)->update(['task_name' => $task_name, 'completed' => $completed]);
            Session::flash('status', 'Successfully updated task.');

            return redirect(url()->route('home') . '#task-' . $id);
        }

        return $this->task($id)->with('error', $error);
    }

    /**
     * Completes a given task if it exists and belongs to the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeTask($id)
    {
        $this->getTask($id)->update(['completed' => true]);
        Session::flash('status', 'Successfully updated task.');

        return redirect(url()->route('home') . '#task-' . $id);
    }

    /**
     * Deletes a given task if it exists and belongs to the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTask($id)
    {
        $this->getTask($id)->delete();
        Session::flash('status', 'Successfully deleted task.');

        return redirect()->route('home');
    }
}
