@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Profile Picture') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                                <span class="display-4 text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('profile.picture.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row mb-4">
                            <label for="avatar" class="col-md-4 col-form-label text-md-right">{{ __('Choose a new profile picture') }}</label>

                            <div class="col-md-6">
                                <input id="avatar" type="file" class="form-control-file @error('avatar') is-invalid @enderror" name="avatar" required>

                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Accepted formats: jpeg, png, jpg, gif. Max size: 2MB
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Profile Picture') }}
                                </button>
                                @if($user->avatar)
                                    <a href="{{ route('profile.picture.destroy') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('delete-picture-form').submit();">
                                        {{ __('Remove Picture') }}
                                    </a>
                                @endif
                                <a href="{{ route('profile.index') }}" class="btn btn-link">
                                    {{ __('Back to Profile') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($user->avatar)
                        <form id="delete-picture-form" action="{{ route('profile.picture.destroy') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
