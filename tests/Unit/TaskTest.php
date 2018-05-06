<?php
use App\User;
use App\Task;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest  extends TestCase
{
	use DatabaseMigrations;

    private function loginUser(User $user){

        Auth::login($user);
    
        return $user;
    }

    /** @test */
    function an_view_login_form()
    {
        
       $response = $this->get('/login');
       $response->assertStatus(200);
       $response->assertSee("Login");

    }
    /** @test */
    function can_view_register_form()
    { 
       $response = $this->get('/register');
       $response->assertStatus(200);
       $response->assertSee("Register");
    }

    /** @test */
    function user_can_logout()
    {  
       $response = $this->post('/logout');
       $logged_in_user = Auth::user();
       $response->assertStatus(302);
       $this->assertNull($logged_in_user);
    }

    /** @test */
    function loggedout_user_cant_access_task_list_throw_exception()
    {
        $this->disableExceptionHandling();

            $this->expectException(AuthenticationException::class);

            $this->response = $this->json('GET', '/tasks'); 
            
            return;
        
        $this->fail("Must be logged in to view your task list");        

    }

        /** @test */
    function loggedin_user_can_access_task_list()
    {

        $user  = factory(User::class)->create([
            "name"=>'Joe Bloggs',
        ]);

        $user = $this->loginUser($user);

        $response = $this->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee("Add Task");
        
    }

    /** @test */
    function loggedin_user_can_add_a_task()
    {
        // get new user
        $user  = factory(User::class)->create([
            "name"=>'John Smith',
        ]);

        $user = $this->loginUser($user);
        $user = Auth::user();
        $task = $user->addTask("Task Name");

        $this->assertNotNull($task);
        $this->assertEquals(1, $user->tasks()->tasksLeft());
    }

    /** @test */
    function logged_out_user_can_not_add_task_throw_exception()
    {
        $this->disableExceptionHandling();

        $user  = factory(User::class)->create([
            "name"=>'Joe Bloggs',
        ]);

        $user->addTask('do the shopping');

        $user->addTask('mow the lawn');
        
        try {

            $params = [

                'name' => "Task Name",
                'user_id' => $user->id,
            ];

            $response = $this->json('POST', "/task/", $params);    

        } catch (AuthenticationException $e) {

            $amount = $user->tasks()->tasksLeft();           
            $this->assertEquals(2, $user->tasks()->tasksLeft());
            return;
        }

        $this->fail("You must be logged into create a task, there are " . $user->tasks()->tasksLeft() . " tasks for the user " . $user->name);

    }

    /** @test */
    function user_can_delete_task()
    {
        $this->disableExceptionHandling();

        // get new user
        $user  = factory(User::class)->create([
            "name"=>'John Smith',
        ]);

        $user = $this->loginUser($user);

        $task = $user->addTask('pay the phone bill');

        $response = $this->json('POST', "/task/".$task->id, ['_token' => csrf_token(), '_method'=>'DELETE']);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(0, $user->tasks()->TasksLeft());
    }
}
