<div class="card mt-3">
    <ul>
        @foreach ($project->activity as $activity)
        <li>
            @include("projects.activity." .$activity->description)
               <span class="text-grey">{{$activity->updated_at->diffForHumans()}}</span>
        </li>
        @endforeach
    </ul>
</div>
