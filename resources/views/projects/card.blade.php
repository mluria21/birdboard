
<div class="card" style="height: 200px">
    <h3 class="font-normal text-xl py-4 border-l-4 border-blue -ml-5 pl-5">
    <a href="{{$project->path()}}">{{$project->title}}</a>
    </h3>

    <div class="text-grey">{{Str::limit($project->description,100)}}</div>
</div>
