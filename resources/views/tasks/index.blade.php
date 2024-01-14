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
            <form action="" method="get">
                <select class="form-control my-2" name="project" id="project_id" onchange="$(this).closest('form').submit()">
                    <option value="">All projects</option>
                    @foreach ($projects as $project)
                        <option {{ request('project') == $project->id ? 'selected' : '' }} value="{{ $project->id }}">
                            {{ $project }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-sm-6 clearfix my-2">
            <a href="{{ route('projects.index') }}" class="float-right btn btn-primary btn-sm ml-3 ">
                Projects
            </a>
            <button type="button" class="float-right btn btn-success btn-sm" data-toggle="modal" data-target="#addTask">
                Add task
            </button>
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
                                    <label for="">Name *</label>
                                    <input type="text" required name="name" placeholder="Enter the task"
                                        class="form-control  @error('name')  is-invalid @enderror">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Project (You may first create project)</label>
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
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="editTask" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" id="editName" name="name" placeholder="Enter the task name"
                                    class="form-control">
                                <input type="hidden" id="taskId">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <select name="project_id" class="form-control  @error('name')  is-invalid @enderror"
                                    id="projectId">
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
                    <button class="save-button btn btn-primary" onclick="updateTask(event)">Save</button>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var t
        $(function() {
            var sortData = {
                task: null,
                destination: null,
                index: null
            }
            var sortable = $("#task-list").sortable({
                start: function(e, ui) {
                    sortData.index = ui.item.index()
                },
                stop: function(e, ui) {
                    var item = ui.item
                    if (sortData.index > item.index()) {
                        sortData.task = item.data('task-id')
                        sortData.destination = item.next().data('task-id')
                    } else {
                        sortData.task = item.data('task-id')
                        sortData.destination = item.prev().data('task-id')
                    }
                    console.log(sortData);
                    $.ajax({
                        url: "{{ route('tasks.updatePriority') }}",
                        data: sortData,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res, status) {
                            if (status != 'success' || res.status != 'success') {
                                sortable.sortable('cancel');
                                alert('Cannot update record!')
                            }
                        },
                        error: function(xhr, status, error) {
                            sortable.sortable('cancel');
                            alert('Error occured')
                        }
                    })
                },
            });
        });
    </script>

    <script>
        function deleteTask() {
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
        }

        function editTask(e) {
            var elem = $(e.target)
            $('#editName').val(elem.data('task-name'));
            $('#taskId').val(elem.data('task-id'));
            $('#projectId').val(elem.data('task-project-id'));
        }

        function updateTask() {
            var taskId = $('#taskId').val();
            var edited = $('#editName').val();
            var selectedProjectId = $('#editTask select[name="project_id"] option:selected').val();
            if (edited == '') {
                alert('Name cannot be empty');
                return
            }
            $.ajax({
                url: "{{ route('tasks.update') }}",
                type: 'PUT',
                data: {
                    name: edited,
                    project_id: selectedProjectId,
                    task_id: taskId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res, status) {
                    if (status == 'success' && res.status == 'success') {
                        $('#task-name-' + taskId).html(res.data.name)
                        $('#editTask').modal('hide');
                    } else {
                        alert('Failed')
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error occured')
                }
            })
        }
    </script>
@endsection
