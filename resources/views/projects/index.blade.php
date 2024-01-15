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
        <button type="button" class="mx-2 btn btn-success" data-toggle="modal" data-target="#addProject">
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
                                <input type="text" id="editName" class="form-control"
                                    placeholder="Enter the project name" />
                                <input type="hidden" id="projectId" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="save-button btn btn-primary" onclick="updateProject()">Save</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        function updateProject() {
            var projectId = $('#projectId').val()
            var edited = $('#editName').val();
            if (edited == '') {
                alert('Name cannot be empty');
                return
            }

            $.ajax({
                url: "{{ route('projects.update') }}",
                type: 'PUT',
                data: {
                    name: edited,
                    project_id: projectId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res, status) {
                    if (status == 'success' && res.status == 'success') {
                        $('#project-name-' + projectId).html(res.data.name)
                        $('#editProject').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error occured')
                }
            })
        }
        function deleteProject(e) {
            var projectId = $(e.target).data('project-id');
            $.ajax({
                url: "{{ route('projects.destroy') }}/" + projectId,
                type: 'DELETE',                
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response, status) {
                    if (status == 'success' && response.status == 'success') {
                        $('#project-' + projectId).remove()
                    } else {
                        alert('Unable to delete')
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error occured')
                }
            })
        }
        function editProject(e) {
            var elem = $(e.target)
            $('#editName').val(elem.data('project-name'));
            $('#projectId').val(elem.data('project-id'));
        }
    </script>
@endsection
