<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Session;
use Gate;
use Storage;
use Response;
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;
use App\TList;
use App\ListImage;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(TList::class, 'list');
    }

    protected function processImage($uploaded_file)
    {        
        $image = Image::make($uploaded_file);
        $image->fit(config('images.width'), config('images.height'), 
                    function ($constraint) {
                        $constraint->upsize();
                    });

        if (config('images.watermark')) {
            try {
                $image->insert(base_path() . config('images.watermark'),
                               'bottom-left', 10, 10);
            } catch (NotReadableException $e) {

            }
        }

        $image->encode('jpg', 75);

        $file_path = config('images.storage') . uniqid() . '.jpg';

        Storage::put($file_path, $image->getEncoded());

        return $file_path;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('lists', ['lists'=>$user->lists,'shared_lists'=>$user->shared_lists, 'name'=>$user->name]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create-list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:100',
            'image'=>'mimes:jpeg,png|dimensions:min_width='.config('images.width').',min:height='.config('images.height').'|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect(route('lists.create'))->withErrors($validator)->withInput();
        }



        $list = new TList;
        $list->user()->associate(Auth::user());
        $list->name = $request->input('name');
        $list->public = $request->has('public');
        $list->save();

        if ($request->hasFile('image')) {
            $image = new ListImage;
            $image->list()->associate($list);
            $image->path = $this->processImage($request->file('image'));
            $image->save();
        }

        Session::flash('status', 'Successfully created list.');

        return redirect(route('lists.show', $list));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TList $list)
    {
        $response = Response::view('list', ['list'=>$list, 'tasks'=>$list->tasks()->orderBy('position', 'asc')->get()]);
        if ($list->public && !Auth::user() && config('app.push_header')) {
            $response->header('Link', config('app.push_header'));
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TList $list)
    {
        return view('edit-list', ['list'=>$list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TList $list)
    {
        $request->validate(['name'=>'required|max:100',
                            'image'=>'mimes:jpeg,png|dimensions:min_width='.config('images.width').',min:height='.config('images.height').'|max:2000',]);

        $list->name = $request->input('name');
        $list->public = $request->has('public');
        $list->save();

        if ($request->hasFile('image')) {
            if ($list->image) {
                $image = $list->image;
                Storage::delete($image->path);
            } else {
                $image = new ListImage;
                $image->list()->associate($list);
            }
            
            $image->path = $this->processImage($request->file('image'));
            $image->save();
        }

        Session::flash('status', 'Successfully updated list.');

        return redirect(route('lists.show', $list));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TList $list)
    {
        $list->delete();

        Session::flash('status', 'Successfully deleted list.');

        return redirect(route('lists.index'));
    }
}
