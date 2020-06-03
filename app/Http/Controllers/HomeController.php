<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\FrontpageList;

class HomeController extends Controller
{
    public function index() {
	    	$response = Response::view('welcome', ['lists'=>FrontpageList::all()->sortByDesc('created_at')->take(5)]);
	    	if (config('app.push_header')) {
	    	    $response->header('Link', config('app.push_header'));
	    	}
	    	return $response;
    }
}
