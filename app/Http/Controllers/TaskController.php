<?php

namespace App\Http\Controllers;

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


    public function index(){



    }

    /**
	 * Create a new task.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
	    $this->validate($request, [
	        'name' => 'required|max:255',
	    ]);

	    $request->user()->tasks()->create([
	        'name' => $request->name,
	    ]);

	    return redirect('/tasks');
	}
}
