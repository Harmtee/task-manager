@extends('layouts.app')
@section('css')
    <style>
        #project-list {
            height: 75vh;
        }
    </style>
@endsection

@section('content')
    <h1 class="text-center">Projects</h1>
    <hr>
    <div class="d-flex justify-content-end my-2">
        <button type="button" class="mx-2 btn btn-primary" data-toggle="modal" data-target="#addProject">
            Add Project
        </button>
        <a href="{{ route('tasks.index') }}" class="ml-2 btn btn-primary btn-sm">
            Go to tasks
        </a>
    </div>



    <div id="project-list" class="d-flex justify-content-center card w-sm-50">
        <div class="card-body d-flex flex-column">
            @forelse ($projects as $project)
                @include('projects.includes.project-item')
            @empty
                <div class="align-self-center text-center flex-grow-1">No project available</div>
            @endforelse
        </div>

    </div>

    <div class="modal fade" id="addProject" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Enter the project name"
                                        class="form-control @error('name')  is-invalid @enderror">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add project</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="editProject" role="dialog">
        <div class="modal-dialog">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Enter the project name"
                                    class="form-control">
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
@endsection
@section('script')
    <script>
        $(function() {
            $('.delete-button').on('click', function() {
                var projectId = $(this).data('project-id');
                // Send the AJAX request to delete the project
                $.ajax({
                    url: "{{ route('projects.destroy') }}/" + projectId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response, status) {
                        if (status == 'success' && response == 1) {
                            $('#project-' + projectId).remove()
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error occured')
                    }
                })
            });
            $('.edit-button').on('click', function() {
                var projectName = $(this).data('project-name');
                var projectId = $(this).data('project-id');
                $('#editProject input[name="name"]').val(projectName)
                $('#editProject button.save-button').data('project-id', projectId)
            });
            $('.save-button').on('click', function() {
                var projectId = $(this).data('project-id');
                // Send the AJAX request to update the project
                var edited = $('#editProject input[name="name"]').val();
                if (edited == '') {
                    alert('Name cannot be empty');
                    return
                }
                $.ajax({
                    url: "{{ route('projects.update') }}/" + projectId,
                    type: 'PUT',
                    data: {name: edited},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(project, status) {
                        if (status == 'success' && project != 0) {
                            $('#project-name-' + projectId).html(project.name)
                            $('#editProject').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error occured')
                    }
                })
            });
        })
    </script>
@endsection
