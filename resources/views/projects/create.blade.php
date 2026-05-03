@extends('layouts.app')

@section('title', 'New Project')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-1 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Projects
        </a>
        <h1 class="text-3xl font-bold text-white">New Video Project</h1>
        <p class="text-gray-400 mt-1">Start your video production journey</p>
    </div>

    <form action="{{ route('projects.store') }}" method="POST"
          class="bg-gray-900 border border-gray-800 rounded-2xl p-8 space-y-6">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Project Title <span class="text-red-400">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                   placeholder="e.g. Product Launch Video 2026" autofocus>
            @error('title')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description <span class="text-gray-500">(optional)</span></label>
            <textarea id="description" name="description" rows="4"
                      class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition resize-none"
                      placeholder="What is this video about?">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-3 rounded-lg transition">
                Create Project
            </button>
            <a href="{{ route('projects.index') }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-6 py-3 rounded-lg transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
