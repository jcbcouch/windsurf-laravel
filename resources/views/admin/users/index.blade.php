@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Users Management</h2>
                    <div class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Actions</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary me-1 mb-1">
                                                    {{ $role->name }}
                                                    <form action="{{ route('admin.users.roles.remove', ['user' => $user->id, 'role' => $role->id]) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to remove this role?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-sm text-white p-0 ms-1" 
                                                                style="line-height: 1; vertical-align: middle;">
                                                            &times;
                                                        </button>
                                                    </form>
                                                </span>
                                            @endforeach
                                            @if($user->roles->isEmpty())
                                                <span class="text-muted">No roles assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.roles.assign', $user->id) }}" method="POST" class="d-flex">
                                                @csrf
                                                <select name="role_id" class="form-select form-select-sm me-2" style="width: auto;">
                                                    <option value="">Select Role</option>
                                                    @foreach($allRoles as $role)
                                                        @if(!$user->roles->contains('id', $role->id))
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Add Role
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
