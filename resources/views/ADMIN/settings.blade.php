@extends('ADMIN.layout')

@section('title', 'Admin Settings')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">⚙️ Admin Settings</h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Admin Profile Card --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">👤 Admin Profile</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5>{{ $admin->name }}</h5>
                    <p class="text-muted">{{ $admin->email }}</p>
                    <p class="text-muted">
                        <span class="badge bg-{{ $admin->role === 'super_admin' ? 'danger' : 'primary' }}">
                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                        </span>
                    </p>
                    @if($admin->bio)
                        <p class="text-muted">{{ $admin->bio }}</p>
                    @endif
                    @if($admin->last_login_at)
                        <small class="text-muted">Last login: {{ $admin->last_login_at->format('M d, Y H:i') }}</small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Profile Update Form --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">✏️ Update Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current avatar</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $admin->bio) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- System Information --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">📊 System Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Users:</strong> {{ $systemInfo['total_users'] ?? 0 }}</p>
                            <p><strong>Total Games:</strong> {{ $systemInfo['total_games'] ?? 0 }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Active Subscriptions:</strong> {{ $systemInfo['active_subscriptions'] ?? 0 }}</p>
                            <p><strong>Total Revenue:</strong> ₹{{ number_format($systemInfo['total_revenue'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout Section --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">🚪 Session Management</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Click below to securely logout from the admin panel.</p>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>
            </div>
        </div>
    </div>
</div>

{{-- Change Password Modal --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.password.change') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">🔒 Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.alert {
    border: none;
    border-radius: 0.375rem;
}

.btn {
    border-radius: 0.375rem;
}

.form-control {
    border-radius: 0.375rem;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection
