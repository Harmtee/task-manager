@extends('layouts.app')
@section('css')
    <style>
        #task-list {
            height: 75vh;
        }
    </style>
@endsection

@section('content')
    <h1 class="text-center">Task Manager</h1>
    <hr>
    <div class="row">

        <div class="col-sm-6 form-group">
            {{-- <form action="" method="get">
                <select class="form-control my-2" name="project" id="project_id" onchange="$(this).closest('form').submit()">
                    <option value="">Select project</option>
                    @foreach ($projects as $project)
                        <option {{ request('project') == $project->id ? 'selected' : '' }} value="{{ $project->id }}">
                            {{ $project }}</option>
                    @endforeach
                </select>
            </form> --}}
        </div>
        <div class="col-sm-6 clearfix my-2">
            <button type="button" class="float-right btn btn-primary btn-sm ml-3" data-toggle="modal"
                data-target="#addTask">
                Add task
            </button>
            <a href="{{ route('projects.index') }}" class="float-right btn btn-primary btn-sm ">
                Projects
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-center card w-sm-50">
        <div id="task-list" class="card-body d-flex flex-column">
            @forelse ($tasks as $task)
                @include('tasks.includes.task-item')
            @empty
                <div class="align-self-center text-center flex-grow-1">No task available</div>
            @endforelse
        </div>

    </div>
    <div class="modal fade" id="addTask" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Enter the task"
                                        class="form-control  @error('name')  is-invalid @enderror">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="project_id" class="form-control  @error('name')  is-invalid @enderror"
                                        id="project_id">
                                        <option value="">Select project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add task</button>
                    </div>
            </form>
        </div>
    </div>
    </div>

    <div class="modal fade" id="editTask" role="dialog">
        <div class="modal-dialog">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Enter the task name" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <select name="project_id" class="form-control  @error('name')  is-invalid @enderror"
                                    id="project_id">
                                    <option value="">Select project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="save-button btn btn-primary">Save</button>
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#task-list").sortable({
                update: function(event) {
                    // Get the updated order of tasks
                    var taskIds = [];
                    $('#task-list .task').each(function() {
                        taskIds.push($(this).data('task-id'));
                    });

                    // Send the updated order to the server
                    $.ajax({
                        url: '{{ route('tasks.updatePriority') }}',
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            taskIds: taskIds
                        },
                        success: function(response) {
                            // Handle success response if needed
                        },
                        error: function(xhr, status, error) {
                            // Handle error response if needed
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $('.delete-button').on('click', function() {
            var taskId = $(this).data('task-id');
            // Send the AJAX request to delete the task
            $.ajax({
                url: "{{ route('tasks.destroy') }}/" + taskId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response, status) {
                    if (status == 'success' && response == 1) {
                        $('#task-' + taskId).remove()
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error occured')
                }
            })
        });
        $('.edit-button').on('click', function() {
            var taskName = $(this).data('task-name');
            var taskId = $(this).data('task-id');
            var taskProjectId = $(this).data('task-project-id');
            $('#editTask input[name="name"]').val(taskName)
            $('#editTask select[name="project_id"] option[value="' + taskProjectId + '"]').prop('selected', true)
            $('#editTask button.save-button').data('task-id', taskId)
        });
        $('.save-button').on('click', function() {
            var taskId = $(this).data('task-id');
            // Send the AJAX request to update the task
            var edited = $('#editTask input[name="name"]').val();
            var selectedProjectId = $('#editTask select[name="project_id"] option:selected').val();
            if (edited == '') {
                alert('Name cannot be empty');
                return
            }
            $.ajax({
                url: "{{ route('tasks.update') }}/" + taskId,
                type: 'PUT',
                data: {
                    name: edited,
                    project_id: selectedProjectId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(task, status) {
                    if (status == 'success' && task != 0) {
                        $('#task-name-' + taskId).html(task.name)
                        $('#editTask').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error occured')
                }
            })
        });
    </script>
@endsection
