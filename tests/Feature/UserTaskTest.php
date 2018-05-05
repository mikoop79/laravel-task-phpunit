<?php
use App\User;
use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTaskTest  extends TestCase
{
	use DatabaseMigrations;
    /** @test */
    function user_can_view_tasks()
    {
        //$user = User::find(1);
        $task = factory(Task::class)->create([
        	"name"=>'Mow the lawn',
        ]);

        $this->assertNotNull($task);
        //$this->assertNotNull($task);

    }

    public function user_can_create_a_task()
    {
        // arrange
        $user = factory(User::class)->create([
            
            "name"=>'Michael',
            "email"=> 'mikoop@mac.com'
        ]);

        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            "name"=>'Mow the lawn',
        ]);

        
        $this->assertTrue($task->count() > 0);
    }

    /** @test */
    function user_can_view_login_form()
    {
        
       $response = $this->get('/login');
       //$this->visit('/login');
       $response->assertStatus(200);
    }

    function user_can_view_register_form()
    {
        
       $response = $this->get('/register');
       //$this->visit('/login');
       $response->assertStatus(200);
    }


    function user_can_logout()
    {
        
       $response = $this->post('/logout');
       //$this->visit('/login');
       $response->assertStatus(200);
    }

    /** @test */
    function user_can_not_access_task_without_loggin_in()
    {
        $response = $this->get('/tasks');
        $response->assertStatus(302);

    }

        /** @test */
    function loggedin_user_can_access_task_list()
    {

        $user  = factory(User::class)->create([
            "name"=>'ted',
        ]); 

        $loggedin_user = 
        $response = $this->get('/tasks');
        $response->assertStatus(200);
        
    }
}