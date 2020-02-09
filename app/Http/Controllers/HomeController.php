<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Log;
use Illuminate\Http\Request;

class HomeController extends Controller
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
            Log::info('Task user id ' . $task_user_id);
            return $task_query;
        }
    }

    /**
     * Show the task list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($error = false)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user_name = $user->name;

        $tasks = DB::table('tasks')->where('user_id', $user_id)->get();

        return view('home', ['name' => $user_name, 'tasks' => $tasks, 'error' => $error]);
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
        }

        return $this->index($error);
    }

    /**
     * Completes a given task if it exists and belongs to the current user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeTask($id)
    {
        $this->getTask($id)->update(['completed' => true]);

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

        return redirect()->route('home');
    }
}
