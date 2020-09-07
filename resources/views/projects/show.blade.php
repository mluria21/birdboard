@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4 ">
        <div class="flex justify-between w-full items-center">
            <p class="text-grey">
                <a href="/projects">My Projects</a>
                / {{$project->title}}
            </p>
            <a class="text-grey button" href="{{$project->path().'/edit'}}">Edit Project</a>
        </div>
    </header>

    <main>
        <h2 class="text-grey text-lg mb-3">Tasks</h2>
        <div class="flex -mx-3">
            <div class="w-3/4 px-3">
                <div class="mb-8">

                    @forelse ($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{$task->path()}}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="flex">
                                    <input class="w-full" value="{{$task->body}}" type="text" name="body">
                                    <input type="checkbox" name="completed" id="" onchange="this.form.submit()" {{$task->completed ? 'checked':''}}>
                                </div>
                            </form>
                        </div>
                    @empty

                    @endforelse
                    <div class="card mb-3">
                        <form action="{{$project->path().'/tasks'}}" method="POST">
                            @csrf
                            <input name="body" type="text" placeholder="Add a new task..." class="w-full">
                        </form>
                    </div>

                </div>

                <div class="mb-8">
                    <h2 class="text-grey text-lg mb-3">General Notes</h2>

                    <form action="{{$project->path()}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea class="card w-full mb-4"
                        style="min-height: 200px"
                        placeholder="Notes..."
                        name="notes"
                        >{{$project->notes}}</textarea>

                        <button class="button" type="submit">Save</button>
                    </form>
                </div>
            </div>
            <div class="w-1/4 px-3">
                @include('projects.card')
                @include('projects.activity.card')
            </div>
        </div>
    </main>

@endsection
