<div class="project rounded border mb-1 p-2 d-flex justify-content-between" id="project-{{ $project->id }}">
    <h3 id="project-name-{{ $project->id }}">
        {{ $project->name }}
    </h3>
    <div class="">
        <button class="btn btn-sm btn-primary edit-button" data-toggle="modal" data-target="#editProject"
            onclick="editProject(event)" data-project-name="{{ $project->name }}"
            data-project-id="{{ $project->id }}">Edit</button>
        <button data-project-id="{{ $project->id }}" class="btn btn-sm btn-danger" onclick="deleteProject(event)">Delete</button>
    </div>
</div>

