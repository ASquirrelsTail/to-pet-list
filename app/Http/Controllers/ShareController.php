<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Facades\Mail;

use App\TList;
use App\Share;
use App\User;
use App\Mail\ListShared;

class ShareController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Share::class, 'share');
    }

    protected function checkShareOnList(TList $list, Share $share)
    {
        if ($share->list != $list) {
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
        return redirect(route('lists.edit', ['list'=>$list]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TList $list)
    {
        return view('create-share', ['list'=>$list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TList $list)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255',
            function ($attribute, $value, $fail) {
                if (strtolower($value) == Auth::user()->email) {
                    $fail('You can\'t share this list with yourself!');
                }
            },
            function ($attribute, $value, $fail) use ($list) {
                if ($list->shares->where('email', strtolower($value))->first()) {
                    $fail('You can\'t share this list with the same person twice!');
                }
            },]
        ]);

        $share = new Share;
        $share->list()->associate($list);
        $share->email = strtolower($request->input('email'));
        
        $share->complete = $request->has('complete');
        $share->create = $request->has('create');
        $share->update = $request->has('update');
        $share->delete = $request->has('delete');
        $share->save();

        Session::flash('status', 'Successfully shared list.');

        // Using just the sandbox domain with mailgun this will throw an exception for unauthoried addresses
        try {
            Mail::to($share->email)->send(new ListShared(Auth::user(), $share->user));
        } catch {
            
        }
        

        return redirect(route('lists.show', $list));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TList $list, Share $share)
    {
        $this->checkShareOnList($list, $share);
        return redirect(route('shares.index', $list) . '#share-' . $share->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TList $list, Share $share)
    {
        $this->checkShareOnList($list, $share);
        return view('edit-share', ['list'=>$list, 'share'=>$share]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TList $list, Share $share)
    {
        $this->checkShareOnList($list, $share);

        $share->complete = $request->has('complete');
        $share->create = $request->has('create');
        $share->update = $request->has('update');
        $share->delete = $request->has('delete');
        $share->save();

        return redirect(route('shares.index', $list) . '#share-' . $share->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TList $list, Share $share)
    {
        $this->checkShareOnList($list, $share);

        $share->delete();

        Session::flash('status', 'Successfully stopped sharing list with' . $share->email . '.');

        return redirect(route('shares.index', $list));
    }
}
