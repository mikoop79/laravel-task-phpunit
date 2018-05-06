<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{

	protected $tasks;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }


    public function index(Request $request){

        try {

            $tasks = Task::where('user_id', $request->user()->id)->get();

        } catch (AuthenticationException $e) {

            return response()->json([], 422);
        
        }

    	return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]
    );
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

        try {

            $request->user()->addTask($request->name);

        } catch (AuthenticationException $e) {

            return response()->json([], 422);
        
        }

	    return redirect('/tasks');
	}

    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  string  $taskId
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
       $this->authorize('destroy', $task);

       $task->delete();

       return redirect('/tasks');
    }
}
