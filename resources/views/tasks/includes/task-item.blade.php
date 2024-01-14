<div class="task rounded border mb-1 p-2 d-flex justify-content-between" data-task-id="{{ $task->id }}"
    data-task-priority="{{ $task->priority }}" data-task-project-id="{{ $task->project_id }}"
    id="task-{{ $task->id }}">
    <h3 id="task-name-{{ $task->id }}">
        {{ $task->name }}
    </h3>
    <div class="">
        <button class="btn btn-sm btn-primary edit-button" data-toggle="modal" data-target="#editTask"
            data-task-project-id="{{ $task->project_id }}" data-task-name="{{ $task->name }}"
            data-task-id="{{ $task->id }}" onclick="editTask(event)">Edit</button>
        <button data-task-id="{{ $task->id }}" class="btn btn-sm btn-danger delete-button"
            onclick="deleteTask(event)">Delete</button>
    </div>
</div>
