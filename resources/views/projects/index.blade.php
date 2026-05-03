@extends('layouts.app')

@section('title', 'All Projects')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white">Video Projects</h1>
        <p class="text-gray-400 mt-1">Manage your video production workflow</p>
    </div>
    <a href="{{ route('projects.create') }}"
       class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 rounded-lg transition">
        + New Project
    </a>
</div>

@if ($projects->isEmpty())
    <div class="text-center py-24 border-2 border-dashed border-gray-700 rounded-2xl">
        <svg class="mx-auto w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
        <p class="text-gray-400 text-lg mb-4">No projects yet</p>
        <a href="{{ route('projects.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-3 rounded-lg transition">
            Create your first project
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($projects as $project)
            @php
                $colorMap = ['draft' => 'gray', 'in_progress' => 'yellow', 'completed' => 'green'];
                $color = $colorMap[$project->status];
                $labelMap = ['draft' => 'Draft', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
            @endphp
            <a href="{{ route('projects.show', $project) }}"
               class="group bg-gray-900 border border-gray-800 hover:border-indigo-500 rounded-2xl p-6 flex flex-col gap-4 transition">
                <div class="flex items-start justify-between">
                    <h2 class="text-lg font-semibold text-white group-hover:text-indigo-400 transition line-clamp-2">
                        {{ $project->title }}
                    </h2>
                    <span class="ml-3 shrink-0 px-2.5 py-0.5 text-xs font-semibold rounded-full
                        @if($color === 'green') bg-green-900 text-green-300
                        @elseif($color === 'yellow') bg-yellow-900 text-yellow-300
                        @else bg-gray-800 text-gray-400 @endif">
                        {{ $labelMap[$project->status] }}
                    </span>
                </div>

                @if ($project->description)
                    <p class="text-gray-400 text-sm line-clamp-2">{{ $project->description }}</p>
                @endif

                <div class="mt-auto flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $project->scripts_count }} scripts
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        {{ $project->assets_count }} assets
                    </span>
                    <span class="ml-auto">{{ $project->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
