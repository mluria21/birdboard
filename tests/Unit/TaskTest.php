<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_complete()
    {
        $project = factory('App\Project')->create();

        $tesk = $project->addTask(factory('App\Task',1)->raw());

        dd($tesk->completed);

        $this->assertFalse($tesk->completed);

        $tesk->complete();
        
        $this->assertTrue($tesk->completed);
    }
}
