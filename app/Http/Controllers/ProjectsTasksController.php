<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Task;

class ProjectsTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update',$project);

        $project->addTask(request()->validate([
            'body'=>'required'
        ]));

        return redirect($project->path());
    }

    public function update(Project $project,Task $task)
    {
        $this->authorize('update',$task->project);

        $attributes = request()->validate(['body'=>'required']);

        $task->update($attributes);

        $method = request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}
