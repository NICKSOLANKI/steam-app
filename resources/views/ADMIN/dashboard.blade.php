@extends('front.SS')

@section('content')
<div class="container py-4">
    <h1>Admin Dashboard</h1>
    <p>Welcome to your admin panel 🚀</p>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow rounded-3">
                <div class="card-body text-center">
                    <h5>Total Users</h5>
                    <p class="fs-4 fw-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow rounded-3">
                <div class="card-body text-center">
                    <h5>Verified Users</h5>
                    <p class="fs-4 fw-bold">{{ $verifiedUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm mb-4 rounded-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-2 mb-sm-0">👤 User Management</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">+ Add User</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Password</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->email_verified_at ? '✅ Yes' : '❌ No' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $user->temp_password ?? 'Not set' }}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary me-1 edit-btn"
                                        data-id="{{ $user->id }}"
                                        data-email="{{ $user->email }}"
                                        data-bs-toggle="modal" data-bs-target="#userModal">Edit</button>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="modalForm" class="modal-content needs-validation" novalidate method="POST" action="{{ route('admin.users.save') }}">
                @csrf
                <input type="hidden" name="id" id="userId">

                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add / Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control w-100" id="userEmail" required>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>

                    <div class="mb-3">
                        <label for="userPassword" class="form-label">Password</label>
                        <input type="text" name="password" class="form-control w-100" id="userPassword" minlength="6">
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                        <small class="text-muted">Leave blank to keep current password.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Populate modal for editing
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('userId').value = this.dataset.id;
            document.getElementById('userEmail').value = this.dataset.email;
            document.getElementById('userPassword').value = '';
        });
    });

    // Form validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
