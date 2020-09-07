<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class RecordsActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = app(ProjectFactory::class)->create();

        $this->assertCount(1,$project->activity);
        $this->assertEquals('created',$project->activity[0]->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->create();
        $originalTitle = $project->title;

        $project->update([
            'title'=>'changed'
        ]);

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity) use ($originalTitle){
            $this->assertEquals('updated',$activity->description);

            $expected = [
                'before' => ['title'=> $originalTitle],
                'after' => ['title'=>'changed']
            ];

            $this->assertEquals($expected,$activity->changes);
        });

    }

    /** @test */
    public function creating_a_task()
    {
        $project = app(ProjectFactory::class)->create();

        $project->addTask(['body'=>'task']);

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity){
            $this->assertEquals('task_created',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('task',$activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = app(ProjectFactory::class)->withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'test',
                'completed' => true
            ]);

        $this->assertCount(3,$project->activity);

        tap($project->activity->last(),function($activity){
            $this->assertEquals('task_completed',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('test',$activity->subject->body);
        });
    }

    /** @test */
    public function incomplete_a_task()
    {
        $project = app(ProjectFactory::class)->withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'test',
                'completed' => true
            ]);

        $this->assertCount(3,$project->activity);

        $this->patch($project->tasks[0]->path(),[
                'body' => 'test',
                'completed' => false
            ]);

        $project->refresh();

        $this->assertCount(4,$project->activity);
    }

     /** @test */
     public function deleting_a_task()
     {
        $project = app(ProjectFactory::class)->withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3,$project->activity);
    }
}
