<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    protected static function boot(){

        parent::boot();

        static::created(function($task){
            $task->project->recordActivity('task_created');
        });

        static::updated(function($task){

            if(!$task->completed)return;
            
            $task->project->recordActivity('task_completed');
        });
    }
}
