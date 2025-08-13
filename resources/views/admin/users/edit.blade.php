@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Manage User Roles: {{ $user->name }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        &larr; Back to Users
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Current Roles:</h5>
                        @if($user->roles->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($user->roles as $role)
                                    <div class="badge bg-primary p-2 d-flex align-items-center">
                                        {{ $role->name }}
                                        <form action="{{ route('admin.users.roles.remove', ['user' => $user->id, 'role' => $role->id]) }}" 
                                              method="POST" class="ms-2" 
                                              onsubmit="return confirm('Are you sure you want to remove this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-close btn-close-white" aria-label="Remove"></button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No roles assigned.</p>
                        @endif
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Add Role</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.users.roles.assign', $user->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="role_id" class="form-select" required>
                                        <option value="">Select a role to add</option>
                                        @foreach($allRoles as $role)
                                            @if(!$user->roles->contains('id', $role->id))
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        Add Role
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Email: {{ $user->email }}</span>
                        <span class="text-muted">Member since {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        font-size: 0.9rem;
    }
    .btn-close {
        opacity: 1;
        font-size: 0.7rem;
        padding: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add fade out effect for alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        });
    });
</script>
@endpush
@endsection
