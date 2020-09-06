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
        
        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')
        ]);
        return redirect($project->path()); 
    }
}
