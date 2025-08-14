@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Profile Background Color') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="p-4 rounded d-inline-block" style="background-color: {{ $user->background_color }}; width: 200px; height: 100px;">
                            <p class="mb-0">Preview</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.background.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row mb-4">
                            <label for="background_color" class="col-md-4 col-form-label text-md-right">
                                {{ __('Choose a background color') }}
                            </label>

                            <div class="col-md-6">
                                <input type="color" 
                                       id="background_color" 
                                       name="background_color" 
                                       class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                       value="{{ old('background_color', $user->background_color) }}" 
                                       title="Choose your background color"
                                       required>

                                @error('background_color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Background Color') }}
                                </button>
                                <a href="{{ route('profile.index') }}" class="btn btn-link">
                                    {{ __('Back to Profile') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update preview when color changes
    document.getElementById('background_color').addEventListener('input', function() {
        const preview = document.querySelector('.p-4.rounded');
        if (preview) {
            preview.style.backgroundColor = this.value;
        }
    });
</script>
@endpush
@endsection
