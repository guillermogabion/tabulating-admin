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
                                <label for="" class="card-title">Categories</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <form method="GET" action="{{ route('categories') }}">
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
                                        <td>{{ $item->event_category->name ?? "NULL"}}</td>
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
                                                    <form action="{{ route('category.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
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
                                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon delete-btn" data-toggle="modal" data-id="{{ $item->id }}">
                                                <i class=" mdi mdi-delete text-primary"></i>
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
                <h5 class="modal-title">Set Item Parameters</h5>
            </div>
            <div class="modal-body">
                <form id="detailForm">
                    @csrf
                    <input type="hidden" id="categoryIdDetail" name="id">
                    <div class="form-group">
                        <label for="categoryNameDetail">Name:</label>
                        <input type="text" id="categoryNameDetail" name="categoryNameDetail" class="form-control" required disabled>
                    </div>
                    <hr>

                    <div id="parameterFieldsContainer"></div>

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
    $('.edit-btn').click(function() {
        let eventId = $(this).data('id');
        let eventName = $(this).data('name');

        $('#eventId').val(eventId);
        $('#eventName').val(eventName);
        $('#editModal').modal('show');

    })

    $('.details-btn').click(function() {
        let categoryIdDetail = $(this).data('id');
        let categoryNameDetail = $(this).data('name');

        $('#categoryIdDetail').val(categoryIdDetail);
        $('#categoryNameDetail').val(categoryNameDetail);
        $('#detailModal').modal('show');
    })

    $('.edit-submit-btn').click(function(event) {
        event.preventDefault();

        let eventId = $('#eventId').val();
        let eventName = $('#eventName').val();
        $.post('/category_update', {
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

    $(document).ready(function() {
        $('.delete-btn').click(function(event) {
            event.preventDefault();
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, make an AJAX request to delete the item
                    $.ajax({
                        url: '/category_delete/' + id, // Replace with your actual delete route
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your item has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload(); // Reload the page or update the UI
                            });
                        },
                        error: function(error) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the item.',
                                'error'
                            );
                            console.error(error); // Log the error for debugging
                        }
                    });
                }
            });
        });
    });




    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }

    $(document).ready(function() {
        let parameterIndex = 0;

        $('#addFieldBtn').click(function() {
            parameterIndex++;
            let fieldHTML = `
        <div class="parameter-group mb-3" id="parameterGroup${parameterIndex}">
            <div class="form-row">
                <div class="col">
                    <div class="form-group">
                        <label for="parameter${parameterIndex}">Parameter ${parameterIndex}</label>
                        <input type="text" id="parameters${parameterIndex}" name="parameters[${parameterIndex}][name]" class="form-control" required>
                    </div>
                </div>
                 <div class="col">
                        <div class="form-group">
                            <label for="minimum${parameterIndex}">Minimum Value</label>
                            <input type="text" id="minimum${parameterIndex}" name="parameters[${parameterIndex}][minimum]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="maximum${parameterIndex}">Maximum Value</label>
                            <input type="text" id="maximum${parameterIndex}" name="parameters[${parameterIndex}][maximum]" class="form-control" required>
                        </div>
                    </div>
                 <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger removeFieldBtn" data-id="${parameterIndex}"><i class="mdi mdi-delete text-primary"></i></button>
                </div>
            </div>
        </div>`;
            $('#parameterFieldsContainer').append(fieldHTML);
        });

        $(document).on('click', '.removeFieldBtn', function() {
            let groupId = $(this).data('id');
            $('#parameterGroup' + groupId).remove();
        });

        $('#detailForm').submit(function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let categoryIdDetail = $('#categoryIdDetail').val();
            formData += '&category_id=' + categoryIdDetail;
            console.log(categoryIdDetail)

            $.post('/parameter_add', formData)
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