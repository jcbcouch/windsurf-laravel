@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>All Videos</h1>
                @auth
                    <a href="{{ route('videos.create') }}" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Upload New Video
                    </a>
                @endauth
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($videos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($videos as $video)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none">
                                                <h5 class="mb-1">Video #{{ $video->id }}</h5>
                                            </a>
                                            <small class="text-muted">
                                                Uploaded by {{ $video->user->name }} on {{ $video->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        @can('delete', $video)
                                            <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $videos->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No videos have been uploaded yet.</p>
                            @auth
                                <a href="{{ route('videos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Upload the first video
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
