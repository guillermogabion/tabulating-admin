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
                                <label for="" class="card-title">Users</label>
                            </div>
                            <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add User</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <form method="GET" action="{{ route('users') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request()->query('search') }}">
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
                                        <td>{{ $item->userId }}</td>
                                        <td>{{ $item->name ?? "NULL"}}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ ucfirst($item->role) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="badge 
                                                            @if ($item->status == 'old') badge-success
                                                            @else badge-danger
                                                            @endif
                                                            " type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $item->status == 'old' ? 'Confirmed' : 'Pending' }}
                                                </button>
                                                <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                    <form action="{{ route('users.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
                                                        @csrf
                                                        <input type="hidden" name="status" id="statusInput{{ $item->id }}" value="">
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('old', {{ $item->id }});">Confirm</a>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('new', {{ $item->id }});">Decline</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-userid="{{ $item->userId }}" data-email="{{ $item->email }}" data-role="{{ $item->role }}">
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
                <h5 class="modal-title">Add User</h5>

            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf

                    <div class="form-group">
                        <label for="addUserName">User ID:</label>
                        <input type="text" id="addUserId" name="userId" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addEmail">Email:</label>
                        <input type="email" id="addEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addRole">Select a Role:</label>
                        <select class="form-control" id="addRole" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="instructor">Teacher</option>
                            <option value="executive">Executive</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    {{--
                        <div class="form-group">
                            <label for="addPassword">Password:</label>
                            <input type="password" id="addPassword" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addPasswordConfirmation">Confirm Password:</label>
                            <input type="password" id="addPasswordConfirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        --}}
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
                    <input type="hidden" id="userId" name="id">
                    <div class="form-group">
                        <label for="userFirstName">User ID:</label>
                        <input type="text" id="userUserId" name="userId" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userLastName">Email:</label>
                        <input type="text" id="userEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Select a Role:</label>
                        <select class="form-select" id="userRole" name="role" required>
                            <option value="" disabled>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="instructor">Teacher</option>
                            <option value="executive">Executive</option>
                            <option value="student">Student</option>
                        </select>
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







</script>

@endsection