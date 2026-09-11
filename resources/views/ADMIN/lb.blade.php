@extends('layouts.app')

@section('title', 'User Libraries')

@section('content')
<div class="p-4 w-100">
    <div class="container-fluid">
        <h2 class="mb-4">📚 User Libraries</h2>

        @if(isset($users))
            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Total Games Purchased</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->libraries_count }}</td>
                                <td>
                                    <a href="{{ route('admin.library.user', $user->id) }}" class="btn btn-sm btn-primary">
                                        View Library
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

      
    </div>
</div>
@endsection
