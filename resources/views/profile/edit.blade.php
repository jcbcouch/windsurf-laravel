{{ ... }}

            <!-- Video Upload Section -->
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900">Your Videos</h2>
                    <a href="{{ route('videos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Upload New Video
                    </a>
                </div>

                @if (session('status') === 'video-uploaded')
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        Video uploaded successfully!
                    </div>
                @endif

                @if (session('status') === 'video-deleted')
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        Video deleted successfully!
                    </div>
                @endif

                @if ($user->videos->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        <p>You haven't uploaded any videos yet.</p>
                        <a href="{{ route('videos.create') }}" class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-800">
                            Upload your first video
                        </a>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Videos</h3>
                        <div class="space-y-2">
                            @foreach ($user->videos as $video)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                    <a href="{{ route('videos.show', $video) }}" class="text-blue-600 hover:text-blue-800">
                                        Video #{{ $video->id }}
                                    </a>
                                    <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{ ... }}
