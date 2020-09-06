@extends('layouts.app')

@section('content')
    <div class="card w-2/4 m-auto">
        <form action="{{$project->path()}}" method="POST">
            @csrf
            @method('PATCH')
        
            <h1>Edit Your Project</h1>
        
            <div class="field">
                <label for="title"></label>
                <input 
                    type="text" 
                    class="mt-1 mb-4 focus:outline-none focus:shadow-outline w-full border-gray-300 border block rounded py-2 px-2" 
                    name="title"
                    value="{{$project->title}}"
                >
            </div>
        
            <div class="field">
                <label for="description"></label>
                <textarea 
                    style="min-height: 200px;" 
                    class="mt-1 mb-4 focus:outline-none focus:shadow-outline w-full border-gray-300 border block rounded py-1 px-4" 
                    name="description"
                >{{$project->description}}</textarea>
            </div>
            
            <button type="submit" class="button mr-2">Create Project</button>
            <a href="{{$project->path()}}">Cancel</a>
        </form>

        @if ($errors->any())
            <div class="mt-5 text-sm text-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            
@endsection
