<?php

namespace App\Http\Controllers;

use Auth;
use DB;
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
     * Show the application dashboard.
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
}
