@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" name="role" id="role" class="form-control" value="{{ $user->role }}"
                            readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Change Password</h4>
                </div>
                <div class="card-body">
                    <form id="formChangePassword" action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#formChangePassword").submit(function(e) {
                e.preventDefault();
                let form = $("#formChangePassword")[0];
                let data = new FormData(form);
                $.ajax({
                    method: "POST",
                    url: "/profile/changepassword",
                    enctype: "multipart/form-data",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    async: false,
                    success: function(respon) {
                        Swal.fire({
                            icon: "success",
                            title: "Saved!",
                            text: "Password has been changed",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        $("#formChangePassword")[0].reset();
                    },
                    error: function(e) {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: e.responseJSON.message,
                        });
                    },
                });
            });
        });
    </script>


@endsection
