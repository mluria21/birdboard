<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_records_generates_activity()
    {
        $project = app(ProjectFactory::class)->create();

        $this->assertCount(1,$project->activity);
        $this->assertEquals('created',$project->activity[0]->description);
    }

    /** @test */
    public function updating_a_project_records_activity()
    {
        $project = app(ProjectFactory::class)->create();

        $project->update([
            'title'=>'changed'
        ]);
        $this->assertCount(2,$project->activity);
        $this->assertEquals('updated',$project->activity->last()->description);
    }

    /** @test */
    public function creating_a_task_records_project_activity()
    {
        $project = app(ProjectFactory::class)->create();

        $project->addTask(['body'=>'task']);

        $this->assertCount(2,$project->activity);
        
        $this->assertEquals('task_created',$project->activity->last()->description);
    }

    /** @test */
    public function completing_a_task_records_project_activity()
    {
        $project = app(ProjectFactory::class)->withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'test',
                'completed' => true
            ]);

        $this->assertCount(3,$project->activity);
        
        $this->assertEquals('task_completed',$project->activity->last()->description);
    }
}
