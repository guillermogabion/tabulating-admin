@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row mt-4">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs for switching between tables -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="items-tab" data-bs-toggle="tab" href="#items" role="tab" aria-controls="items" aria-selected="true">Items</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="overall-tab" data-bs-toggle="tab" href="#overall" role="tab" aria-controls="overall" aria-selected="false">Overall</a>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Items Tab -->
                            <div class="tab-pane fade show active" id="items" role="tabpanel" aria-labelledby="items-tab">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <label for="" class="card-title">Results</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-primary calculate-result" data-bs-toggle="modal" data-bs-target="#addModal">Calculate</button>
                                        <button type="button" class="btn btn-primary calculate-all" data-bs-toggle="modal" data-bs-target="#overModal">Calculate Overall</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('results') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search result..." value="{{ request()->query('search') }}">
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
                                                <td>{{ $item->participant_id }}</td>
                                                @if ($item->result_participant)
                                                <td>{{ $item->result_participant->name }}</td>
                                                @else
                                                <td>N/A</td>
                                                @endif
                                                @forelse ($item->event_result as $event)
                                                <td>{{ $event->name }}</td>
                                                @empty
                                                <td>No Event</td>
                                                @endforelse
                                                @if ($item->category_result)
                                                <td>{{ $item->category_result->name }}</td>
                                                @else
                                                <td>Overall Result</td>
                                                @endif
                                                <td>{{ $item->result }}</td>
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

                            <!-- Overall Tab -->
                            <div class="tab-pane fade" id="overall" role="tabpanel" aria-labelledby="overall-tab">
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
                                            @forelse ($overall as $item)
                                            <tr>
                                                <td>{{ $item->participant_id }}</td>
                                                @if ($item->result_participant)
                                                <td>{{ $item->result_participant->name }}</td>
                                                @else
                                                <td>N/A</td>
                                                @endif
                                                @forelse ($item->event_result as $event)
                                                <td>{{ $event->name }}</td>
                                                @empty
                                                <td>No Event</td>
                                                @endforelse
                                                @if ($item->category_result)
                                                <td>{{ $item->category_result->name }}</td>
                                                @else
                                                <td>Overall Result</td>
                                                @endif
                                                <td>{{ $item->result }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Overall Results</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($overall instanceof \Illuminate\Pagination\LengthAwarePaginator || $overall instanceof \Illuminate\Pagination\Paginator)
                                <div class="d-flex justify-content-end">
                                    {{ $overall->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    $(document).ready(function() {
        // Show Add Modal
        $('.calculate-result').click(function() {
            $('#addModal').modal('show');
        });
        $('.calculate-all').click(function() {
            $('#overModal').modal('show');
        });

        $('#addForm').submit(function(e) {
            e.preventDefault();
            let eventId = $('#addEventId').val();
            let participantId = $('#addParticipantId').val();
            let categoryId = $('#addCategoryId').val();

            console.log(participantId, categoryId);

            $.post('/calculate', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                event_id: eventId,
                participant_id: participantId,
                category_id: categoryId
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
        });

        $('#overForm').submit(function(e) {
            e.preventDefault();
            let eventId = $('#overEventId').val();
            let participantId = $('#overParticipantId').val();

            console.log(participantId, eventId);

            $.post('/calculate-all', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                event_id: eventId,
                participant_id: participantId,
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
        });
    });
</script>
@endsection