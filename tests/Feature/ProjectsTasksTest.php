<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Project;
use Tests\Setup\ProjectFactory;

class ProjectsTasksTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks(){

        //$this->withoutExceptionHandling();
        $this->signIn();

        $project = factory(Project::class)->create(['owner_id'=> auth()->id()]);
        //dd($project->path() .'/tasks');
        $this->post($project->path() .'/tasks',['body' => 'meilech']);

        $this->get($project->path())->assertSee('meilech');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        //$this->withoutExceptionHandling();
        $this->signIn();

        $project = factory(Project::class)->create(['owner_id'=> auth()->id()]);
        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() .'./tasks',$attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_owner_of_project_may_add_task()
    {
        //$this->withoutExceptionHandling();
        $this->signIn();

        $project = factory(Project::class)->create();
        $this->post($project->path() .'/tasks',['body' => 'meilech'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks',['body' => 'meilech']);
    }

    /** @test */
    public function a_task_can_b_updated()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->path() .'/tasks/' .$project->tasks[0]->id,[
            'body' => 'changed'
        ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed'
        ]);
    }

    /** @test */
    public function a_task_can_b_completed()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->path() .'/tasks/' .$project->tasks[0]->id,[
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_can_b_incomplete()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->path() .'/tasks/' .$project->tasks[0]->id,[
            'body' => 'changed',
            'completed' => true
        ]);

        $this->patch($project->path() .'/tasks/' .$project->tasks[0]->id,[
            'body' => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed',
            'completed' => false
        ]);
    }

    /** @test */
    public function only_owner_of_project_may_update_task()
    {
        //$this->withoutExceptionHandling();
        $this->signIn();

        $project = factory(Project::class)->create();
        $task =  $project->addTask(['body' => 'meilech']);

        $this->patch($task->path(),['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks',['body' => 'changed']);
    }
}
