<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function activity(){
        return $this->hasMany(Activity::class);
    }

    public function addTask($body){

        $task = $this->tasks()->create($body);

        return $task;
    }

    public function recordActivity($type)
    {
        \App\Activity::create([
            'project_id'=>$this->id,
            'description'=>$type
        ]);
    }
}
