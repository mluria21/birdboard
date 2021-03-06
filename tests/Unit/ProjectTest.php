<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $project = factory('App\Project')->create();
        $this->assertEquals('/projects/' .$project->id,$project->path());
    }

    /** @test */
    public function has_owner()
    {
        $project = factory('App\Project')->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function can_add_a_task()
    {
        $project = factory('App\Project')->create();
        $task = $project->addTask(['body' => 'meilech']);

        $this->assertCount(1,$project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }
}
