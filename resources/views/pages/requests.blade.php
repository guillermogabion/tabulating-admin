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
                                <label for="" class="card-title">Requests</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Modal -->
<!-- <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
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
</div> -->



<!-- Edit Modal -->
<!-- <div id="editModal" class="modal fade" role="dialog">
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
</div> -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>







</script>

@endsection