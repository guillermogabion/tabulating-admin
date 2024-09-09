@extends('layouts.app')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mt-4">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <label for="" class="card-title">Participants</label>
                            </div>
                            <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Participant</button>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <form method="GET" action="{{ route('participants') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search participant..." value="{{ request()->query('search') }}">
                                        <span class="input-group-append">
                                            <button class="btn btn-outline-secondary d-none" type="submit">Search</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        @foreach($headers as $header)
                                        <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name ?? "NULL"}}</td>
                                        <td>{{ $item->age}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="badge 
                                                            @if ($item->status == 'active') badge-success
                                                            @else badge-danger
                                                            @endif
                                                            " type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $item->status == 'active' ? 'Active' : 'Unavailable' }}
                                                </button>
                                                <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                    <form action="{{ route('participant.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
                                                        @csrf
                                                        <input type="hidden" name="status" id="statusInput{{ $item->id }}" value="">
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('active', {{ $item->id }});">Activate</a>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('unavailable', {{ $item->id }});">Deactivate</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-age="{{ $item->age }}" data-address="{{ $item->address }}" data-other="{{ $item->other }}">
                                                <i class="mdi mdi-lead-pencil text-primary"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="alert alert-info">No Items</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $items->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Modal -->

<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Add Participant</h5>

            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf

                    <div class="form-group">
                        <label for="addName">Name:</label>
                        <input type="name" id="addName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addAge">Age:</label>
                        <input type="text" id="addAge" name="age" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addAddress">Address:</label>
                        <input type="text" id="addAddress" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addOther">Other Information:</label>
                        <textarea id="addOther" name="other" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-heade d-flex justify-content-center">
                <h5 class="modal-title">Update Participant</h5>

            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="participantId" name="id">
                    <div class="form-group">
                        <label for="participantName">Name:</label>
                        <input type="text" id="participantName" name="participantName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="participantAge">Age:</label>
                        <input type="text" id="participantAge" name="age" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="participantAddress">Address:</label>
                        <input type="text" id="participantAddress" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="participantOther">Other Information:</label>
                        <input type="text" id="participantOther" name="other" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    $('.add-item').click(function() {
        $('#addModal').modal('show');
    });

    $('.edit-btn').click(function() {
        let participantId = $(this).data('id');
        let participantName = $(this).data('name');
        let participantAge = $(this).data('age');
        let participantAddress = $(this).data('address');
        let participantOther = $(this).data('other');

        $('#participantId').val(participantId);
        $('#participantName').val(participantName);
        $('#participantAge').val(participantAge);
        $('#participantAddress').val(participantAddress);
        $('#participantOther').val(participantOther);
        $('#editModal').modal('show');

    })



    $(document).ready(function() {
        $('.add-btn').click(function(e) {
            e.preventDefault();

            let participantName = document.getElementById('addName').value
            let participantAge = document.getElementById('addAge').value
            let participantAddress = document.getElementById('addAddress').value
            let participantOther = document.getElementById('addOther').value
            $.post('/participant_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: participantName,
                age: participantAge,
                address: participantAddress,
                other: participantOther,

            }).done(function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Saving Success',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }).fail(function(err) {
                if (err.status === 422) {
                    let errors = err.responseJSON.errors;
                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            console.error(key + ": " + errors[key]);
                        }
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: "Please check the input data.",
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message || "An error occurred",
                        confirmButtonText: 'OK'
                    });
                    console.error(err);
                }
            });

        })
    })

    $('.edit-submit-btn').click(function(event) {
        event.preventDefault();

        let participantId = $('#participantId').val();
        let participantName = $('#participantName').val();
        let participantAge = $('#participantAge').val();
        let participantAddress = $('#participantAddress').val();
        let participantOther = $('#participantOther').val();
        $.post('/participant_update', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: participantId,
                name: participantName,
                age: participantAge,
                address: participantAddress,
                other: participantOther,
            })
            .done(function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Update Success',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            })
            .fail(function(err) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: err.responseJSON.message || "An error occurred",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
                console.error(err);
            });
    });

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection