@extends('layouts.app')

@section('title', 'Edit: ' . $project->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('projects.show', $project) }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-1 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Project
        </a>
        <h1 class="text-3xl font-bold text-white">Edit Project</h1>
    </div>

    <form action="{{ route('projects.update', $project) }}" method="POST"
          class="bg-gray-900 border border-gray-800 rounded-2xl p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Project Title <span class="text-red-400">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                   autofocus>
            @error('title')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Client --}}
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-300 mb-2">Client <span class="text-gray-500">(optional)</span></label>
            <div class="flex gap-2">
                <select id="client_id" name="client_id"
                        class="flex-1 bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white outline-none transition">
                    <option value="">— No client —</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}{{ $client->company ? ' — ' . $client->company : '' }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('clients.create') }}"
                   class="shrink-0 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-400 hover:text-white px-3 py-3 rounded-lg transition text-sm" title="New client">
                    + Client
                </a>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description <span class="text-gray-500">(optional)</span></label>
            <textarea id="description" name="description" rows="4"
                      class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition resize-none">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-3 rounded-lg transition">
                Save Changes
            </button>
            <a href="{{ route('projects.show', $project) }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-6 py-3 rounded-lg transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
