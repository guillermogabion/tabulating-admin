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
                                <label for="" class="card-title">Events</label>
                            </div>
                            <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Event</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <form method="GET" action="{{ route('events') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search event..." value="{{ request()->query('search') }}">
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
                                                    <form action="{{ route('events.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
                                                        @csrf
                                                        <input type="hidden" name="status" id="statusInput{{ $item->id }}" value="">
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('active', {{ $item->id }});">Activate</a>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('unavailable', {{ $item->id }});">Deactivate</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                <i class="mdi mdi-lead-pencil text-primary"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon details-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                <i class="mdi mdi-star-circle text-primary"></i>
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
                <h5 class="modal-title">Add Event</h5>

            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf

                    <div class="form-group">
                        <label for="addEventName">Event Name</label>
                        <input type="text" id="addEvent" name="name" class="form-control" required>
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
                <h5 class="modal-title">Update Item</h5>

            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="eventId" name="id">
                    <div class="form-group">
                        <label for="eventName">Name:</label>
                        <input type="text" id="eventName" name="eventName" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Detail Modal  -->

<div id="detailModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Set Item Category</h5>
            </div>
            <div class="modal-body">
                <form id="detailForm">
                    @csrf
                    <input type="hidden" id="eventIdDetail" name="id">
                    <div class="form-group">
                        <label for="eventNameDetail">Name:</label>
                        <input type="text" id="eventNameDetail" name="eventNameDetail" class="form-control" required disabled>
                    </div>
                    <hr>

                    <div id="categoryFieldsContainer"></div>

                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-success" id="addFieldBtn"><i class="mdi mdi-plus text-primary"></i></button>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 detail-submit-btn">Submit</button>
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
        let eventId = $(this).data('id');
        let eventName = $(this).data('name');

        $('#eventId').val(eventId);
        $('#eventName').val(eventName);
        $('#editModal').modal('show');

    })

    $('.details-btn').click(function() {
        let eventIdDetail = $(this).data('id');
        let eventNameDetail = $(this).data('name');

        $('#eventIdDetail').val(eventIdDetail);
        $('#eventNameDetail').val(eventNameDetail);
        $('#detailModal').modal('show');
    })

    $(document).ready(function() {
        $('.add-btn').click(function(e) {
            e.preventDefault();

            let eventName = document.getElementById('addEvent').value

            $.post('/events_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: eventName
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

        let eventId = $('#eventId').val();
        let eventName = $('#eventName').val();
        $.post('/event_update', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: eventId,
                name: eventName,
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

    $(document).ready(function() {
        let categoryIndex = 0;

        $('#addFieldBtn').click(function() {
            categoryIndex++;
            let fieldHTML = `
        <div class="parameter-group mb-3" id="categoryGroup${categoryIndex}">
            <div class="form-row">
                <div class="col">
                    <div class="form-group">
                        <label for="category${categoryIndex}">Category ${categoryIndex}</label>
                        <input type="text" id="category${categoryIndex}" name="category[${categoryIndex}][name]" class="form-control" required>
                    </div>
                </div>
                 <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger removeFieldBtn" data-id="${categoryIndex}"><i class="mdi mdi-delete text-primary"></i></button>
                </div>
            </div>
        </div>`;
            $('#categoryFieldsContainer').append(fieldHTML);
        });

        $(document).on('click', '.removeFieldBtn', function() {
            let groupId = $(this).data('id');
            $('#categoryGroup' + groupId).remove();
        });

        $('#detailForm').submit(function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let eventIdDetail = $('#eventIdDetail').val();
            formData += '&event_id=' + eventIdDetail;
            console.log(eventIdDetail)

            $.post('/category_add', formData)
                .done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Categories saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                })
                .fail(function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        for (let key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                console.error(key + ": " + errors[key]);
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please check the input data.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: err.responseJSON.message || 'An error occurred',
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    }
                });
        });
    });
</script>

@endsection