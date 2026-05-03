@extends('layouts.app')

@section('title', $project->title)

@section('content')
@php
    $statusColors = [
        'draft'       => ['bg' => 'bg-gray-800', 'text' => 'text-gray-400', 'border' => 'border-gray-600'],
        'in_progress' => ['bg' => 'bg-yellow-900/50', 'text' => 'text-yellow-300', 'border' => 'border-yellow-600'],
        'completed'   => ['bg' => 'bg-green-900/50', 'text' => 'text-green-300', 'border' => 'border-green-600'],
    ];
    $sc = $statusColors[$project->status];
    $statusLabels = ['draft' => 'Draft', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
@endphp

{{-- Header --}}
<div class="mb-8">
    <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-1 transition mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        All Projects
    </a>

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-3xl font-bold text-white">{{ $project->title }}</h1>
                <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $sc['bg'] }} {{ $sc['text'] }} {{ $sc['border'] }}">
                    {{ $statusLabels[$project->status] }}
                </span>
            </div>
            @if ($project->client)
                <a href="{{ route('clients.show', $project->client) }}"
                   class="inline-flex items-center gap-1.5 mt-2 text-sm text-indigo-400 hover:text-indigo-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $project->client->name }}
                    @if ($project->client->company)
                        <span class="text-gray-500">· {{ $project->client->company }}</span>
                    @endif
                </a>
            @endif
            @if ($project->description)
                <p class="text-gray-400 mt-2 max-w-2xl">{{ $project->description }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Status Update --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium px-4 py-2 rounded-lg border border-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Update Status
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute right-0 top-full mt-1 w-44 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-10 overflow-hidden">
                    @foreach (['draft' => 'Draft', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
                        <form method="POST" action="{{ route('projects.status', $project) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $val }}">
                            <button type="submit"
                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-700 transition
                                        {{ $project->status === $val ? 'text-indigo-400 font-semibold' : 'text-gray-300' }}">
                                {{ $label }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('projects.edit', $project) }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium px-4 py-2 rounded-lg border border-gray-700 transition">
                Edit
            </a>

            <form method="POST" action="{{ route('projects.destroy', $project) }}"
                  onsubmit="return confirm('Delete this project and all its data?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="bg-red-900/50 hover:bg-red-800 text-red-300 text-sm font-medium px-4 py-2 rounded-lg border border-red-800 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Main Grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    {{-- LEFT: Scripts (2/3 width) --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Scripts Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Scripts
            </h2>
            <button x-data @click="$dispatch('open-add-script')"
                    class="text-sm bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition">
                + Add Script
            </button>
        </div>

        {{-- Add Script Modal --}}
        <div x-data="{ open: false, title: '', content: '' }"
             @open-add-script.window="open = true"
             x-show="open" x-transition
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             style="display:none">
            <div @click.outside="open = false" class="bg-gray-900 border border-gray-700 rounded-2xl p-8 w-full max-w-2xl shadow-2xl">
                <h3 class="text-lg font-semibold text-white mb-6">Add New Script</h3>
                <form method="POST" action="{{ route('projects.scripts.store', $project) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Script Title</label>
                            <input type="text" name="title" x-model="title" required
                                   class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-2.5 text-white outline-none transition"
                                   placeholder="e.g. Introduction Scene">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Script Content</label>
                            <textarea name="content" rows="8" x-model="content"
                                      class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-2.5 text-white outline-none transition resize-none font-mono text-sm"
                                      placeholder="Write your script here..."></textarea>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 rounded-lg transition">
                                Add Script
                            </button>
                            <button type="button" @click="open = false"
                                    class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-5 py-2.5 rounded-lg transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Script List --}}
        @forelse ($project->scripts as $script)
            <div x-data="{ editing: false, playing: false, content: @js($script->content ?? '') }"
                 class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

                {{-- Script Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                    <h3 class="font-semibold text-white">{{ $script->title }}</h3>
                    <div class="flex items-center gap-2">
                        {{-- TTS Play/Stop --}}
                        <button @click="
                            if (playing) {
                                window.speechSynthesis.cancel();
                                playing = false;
                            } else {
                                let utt = new SpeechSynthesisUtterance(content || '(empty script)');
                                utt.onend = () => playing = false;
                                window.speechSynthesis.speak(utt);
                                playing = true;
                            }
                        "
                        :class="playing ? 'bg-yellow-700 hover:bg-yellow-600 text-yellow-100' : 'bg-teal-700 hover:bg-teal-600 text-white'"
                        class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition"
                        title="Text to Speech">
                            <svg x-show="!playing" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg x-show="playing" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                            <span x-text="playing ? 'Stop' : 'Listen'"></span>
                        </button>

                        <button @click="editing = !editing"
                                class="text-xs bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg transition">
                            <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                        </button>

                        <form method="POST" action="{{ route('projects.scripts.destroy', [$project, $script]) }}"
                              onsubmit="return confirm('Delete this script?')">
                            @csrf @method('DELETE')
                            <button class="text-xs bg-red-900/50 hover:bg-red-800 text-red-300 px-3 py-1.5 rounded-lg transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- View Mode --}}
                <div x-show="!editing" class="px-6 py-4">
                    @if ($script->content)
                        <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-wrap font-mono">{{ $script->content }}</p>
                    @else
                        <p class="text-gray-600 text-sm italic">No content yet. Click Edit to add script text.</p>
                    @endif
                    <p class="text-gray-600 text-xs mt-3">{{ strlen($script->content ?? '') }} characters · Updated {{ $script->updated_at->diffForHumans() }}</p>
                </div>

                {{-- Edit Mode --}}
                <div x-show="editing" class="px-6 py-4">
                    <form method="POST" action="{{ route('projects.scripts.update', [$project, $script]) }}">
                        @csrf @method('PUT')
                        <div class="space-y-3">
                            <input type="text" name="title" value="{{ $script->title }}" required
                                   class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 rounded-lg px-4 py-2.5 text-white outline-none transition text-sm">
                            <textarea name="content" rows="10" x-model="content"
                                      class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 rounded-lg px-4 py-2.5 text-white outline-none transition resize-y font-mono text-sm">{{ $script->content }}</textarea>
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                                Save Script
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-gray-900 border border-gray-800 border-dashed rounded-2xl p-10 text-center">
                <p class="text-gray-500">No scripts yet. Add your first script to get started.</p>
            </div>
        @endforelse

        {{-- Notes Section --}}
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Notes
                </h2>
                <button x-data @click="$dispatch('open-add-note')"
                        class="text-sm bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg font-medium transition">
                    + Add Note
                </button>
            </div>

            {{-- Add Note Modal --}}
            <div x-data="{ open: false, color: 'yellow' }"
                 @open-add-note.window="open = true"
                 x-show="open" x-transition
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 style="display:none">
                <div @click.outside="open = false" class="bg-gray-900 border border-gray-700 rounded-2xl p-8 w-full max-w-lg shadow-2xl">
                    <h3 class="text-lg font-semibold text-white mb-6">Add Note</h3>
                    <form method="POST" action="{{ route('projects.notes.store', $project) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                                <input type="text" name="title" required
                                       class="w-full bg-gray-800 border border-gray-700 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 rounded-lg px-4 py-2.5 text-white outline-none transition"
                                       placeholder="Note title...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Content</label>
                                <textarea name="content" rows="5"
                                          class="w-full bg-gray-800 border border-gray-700 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 rounded-lg px-4 py-2.5 text-white outline-none transition resize-none text-sm"
                                          placeholder="Write your note here..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Color</label>
                                <div class="flex gap-2">
                                    @foreach (['yellow' => 'bg-yellow-500', 'blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'pink' => 'bg-pink-500', 'purple' => 'bg-purple-500'] as $val => $cls)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color" value="{{ $val }}" x-model="color"
                                                   class="sr-only" {{ $val === 'yellow' ? 'checked' : '' }}>
                                            <div :class="color === '{{ $val }}' ? 'ring-2 ring-white ring-offset-2 ring-offset-gray-900' : ''"
                                                 class="{{ $cls }} w-7 h-7 rounded-full transition"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="submit"
                                        class="bg-yellow-600 hover:bg-yellow-500 text-white font-medium px-5 py-2.5 rounded-lg transition">
                                    Add Note
                                </button>
                                <button type="button" @click="open = false"
                                        class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-5 py-2.5 rounded-lg transition">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Notes Grid --}}
            @if ($project->notes->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($project->notes as $note)
                        @php
                            $noteColors = [
                                'yellow' => ['bg' => '#1c1600', 'border' => '#854d0e', 'header' => '#713f12', 'text' => '#fef08a'],
                                'blue'   => ['bg' => '#0c1a2e', 'border' => '#1d4ed8', 'header' => '#1e3a6e', 'text' => '#93c5fd'],
                                'green'  => ['bg' => '#052e16', 'border' => '#15803d', 'header' => '#14532d', 'text' => '#86efac'],
                                'pink'   => ['bg' => '#2d0a1e', 'border' => '#be185d', 'header' => '#831843', 'text' => '#f9a8d4'],
                                'purple' => ['bg' => '#1a0a2e', 'border' => '#7c3aed', 'header' => '#4c1d95', 'text' => '#c4b5fd'],
                            ];
                            $nc = $noteColors[$note->color ?? 'yellow'];
                        @endphp
                        <div x-data="{ editing: false }"
                             class="rounded-2xl border overflow-hidden"
                             style="background-color: {{ $nc['bg'] }}; border-color: {{ $nc['border'] }}">
                            {{-- Note Header --}}
                            <div class="flex items-center justify-between px-4 py-3"
                                 style="background-color: {{ $nc['header'] }}">
                                <span class="font-semibold text-sm truncate" style="color: {{ $nc['text'] }}">
                                    {{ $note->title }}
                                </span>
                                <div class="flex items-center gap-1 ml-2 shrink-0">
                                    <button @click="editing = !editing"
                                            class="p-1 rounded hover:bg-white/10 transition"
                                            style="color: {{ $nc['text'] }}" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('projects.notes.destroy', [$project, $note]) }}"
                                          onsubmit="return confirm('Delete this note?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-white/10 transition text-red-400 hover:text-red-300" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- View Mode --}}
                            <div x-show="!editing" class="px-4 py-3">
                                @if ($note->content)
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap" style="color: {{ $nc['text'] }}; opacity: 0.85">{{ $note->content }}</p>
                                @else
                                    <p class="text-sm italic opacity-40" style="color: {{ $nc['text'] }}">No content.</p>
                                @endif
                                <p class="text-xs mt-2 opacity-40" style="color: {{ $nc['text'] }}">{{ $note->updated_at->diffForHumans() }}</p>
                            </div>

                            {{-- Edit Mode --}}
                            <div x-show="editing" class="px-4 py-3">
                                <form method="POST" action="{{ route('projects.notes.update', [$project, $note]) }}">
                                    @csrf @method('PUT')
                                    <div class="space-y-2">
                                        <input type="text" name="title" value="{{ $note->title }}" required
                                               class="w-full bg-black/30 border border-white/20 rounded-lg px-3 py-2 text-white text-sm outline-none focus:border-white/50 transition">
                                        <textarea name="content" rows="4"
                                                  class="w-full bg-black/30 border border-white/20 rounded-lg px-3 py-2 text-white text-sm outline-none resize-none focus:border-white/50 transition">{{ $note->content }}</textarea>
                                        <div class="flex items-center gap-2">
                                            @foreach (['yellow' => 'bg-yellow-500', 'blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'pink' => 'bg-pink-500', 'purple' => 'bg-purple-500'] as $val => $cls)
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="color" value="{{ $val }}" class="sr-only"
                                                           {{ $note->color === $val ? 'checked' : '' }}>
                                                    <div class="{{ $cls }} w-5 h-5 rounded-full transition
                                                        {{ $note->color === $val ? 'ring-2 ring-white ring-offset-1 ring-offset-gray-900' : '' }}"></div>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="flex gap-2 pt-1">
                                            <button type="submit"
                                                    class="bg-white/20 hover:bg-white/30 text-white text-xs font-medium px-4 py-1.5 rounded-lg transition">
                                                Save
                                            </button>
                                            <button type="button" @click="editing = false"
                                                    class="bg-white/10 hover:bg-white/20 text-white text-xs font-medium px-4 py-1.5 rounded-lg transition">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-900 border border-gray-800 border-dashed rounded-2xl p-8 text-center">
                    <p class="text-gray-500 text-sm">No notes yet. Add a note to keep track of ideas.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: Assets (1/3 width) --}}
    <div class="space-y-6">

        {{-- Asset Upload --}}
        <div x-data="assetUploader()" class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                Assets
            </h2>

            <form method="POST" action="{{ route('assets.store', $project) }}" enctype="multipart/form-data"
                  class="space-y-3">
                @csrf

                <div class="grid grid-cols-3 gap-2">
                    @foreach (['photo' => 'Photo', 'audio' => 'Audio', 'video' => 'Video'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only" required>
                            <div :class="type === '{{ $val }}' ? 'border-indigo-500 bg-indigo-900/50 text-indigo-300' : 'border-gray-700 bg-gray-800 text-gray-400'"
                                 class="border rounded-lg px-3 py-2 text-center text-xs font-medium transition hover:border-indigo-500 hover:text-indigo-300">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>

                <div @dragover.prevent="dragging = true" @dragleave="dragging = false" @drop.prevent="handleDrop($event)"
                     :class="dragging ? 'border-indigo-500 bg-indigo-900/20' : 'border-gray-700'"
                     class="border-2 border-dashed rounded-xl p-6 text-center transition cursor-pointer"
                     @click="$refs.fileInput.click()">
                    <svg class="w-8 h-8 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-400" x-text="fileName || 'Click or drag & drop file'"></p>
                    <input type="file" name="file" x-ref="fileInput" @change="handleFile($event)" class="hidden"
                           :accept="acceptTypes">
                </div>

                <button type="submit" :disabled="!fileName || !type"
                        :class="(fileName && type) ? 'bg-indigo-600 hover:bg-indigo-500 cursor-pointer' : 'bg-gray-700 cursor-not-allowed opacity-50'"
                        class="w-full text-white text-sm font-medium py-2.5 rounded-lg transition">
                    Upload Asset
                </button>
            </form>
        </div>

        {{-- Asset Groups --}}
        @foreach (['photo' => ['Photos', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'], 'audio' => ['Audio', 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'], 'video' => ['Videos', 'M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z']] as $type => [$label, $iconPath])
            @php $typeAssets = $project->assets->where('type', $type) @endphp
            @if ($typeAssets->count() > 0)
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                        </svg>
                        {{ $label }} ({{ $typeAssets->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach ($typeAssets as $asset)
                            <div class="bg-gray-800 rounded-lg px-3 py-2.5 group">
                                @if ($type === 'photo')
                                    <img src="{{ Storage::url($asset->path) }}"
                                         alt="{{ $asset->original_name }}"
                                         class="w-full rounded-lg mb-2 object-cover"
                                         style="max-height:140px">
                                @elseif ($type === 'audio')
                                    <audio controls class="w-full mb-1.5" style="min-width:0">
                                        <source src="{{ Storage::url($asset->path) }}">
                                    </audio>
                                @else
                                    <video controls class="w-full rounded-lg mb-1.5" style="max-height:120px">
                                        <source src="{{ Storage::url($asset->path) }}">
                                    </video>
                                @endif

                                <div class="flex items-center justify-between mt-1">
                                    <div class="min-w-0 mr-2">
                                        <p class="text-xs text-gray-400 truncate" title="{{ $asset->original_name }}">
                                            {{ $asset->original_name }}
                                        </p>
                                        <p class="text-xs text-gray-600">{{ $asset->formatted_size }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition">
                                        {{-- Download --}}
                                        <a href="{{ route('assets.download', $asset) }}"
                                           class="text-indigo-400 hover:text-indigo-300 p-1.5 rounded hover:bg-indigo-900/40 transition"
                                           title="Download {{ $asset->original_name }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('assets.destroy', $asset) }}"
                                              onsubmit="return confirm('Delete this asset?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-400 hover:text-red-300 p-1.5 rounded hover:bg-red-900/40 transition"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if ($project->assets->isEmpty())
            <div class="bg-gray-900 border border-gray-800 border-dashed rounded-2xl p-8 text-center">
                <p class="text-gray-500 text-sm">No assets uploaded yet.</p>
            </div>
        @endif

        {{-- Mark Complete CTA --}}
        @if ($project->status !== 'completed')
            <form method="POST" action="{{ route('projects.status', $project) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mark as Completed
                </button>
            </form>
        @else
            <div class="w-full bg-green-900/50 border border-green-700 text-green-300 font-semibold py-3 rounded-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Project Completed
            </div>
        @endif

    </div>
</div>

<script>
function assetUploader() {
    return {
        type: '',
        fileName: '',
        dragging: false,
        get acceptTypes() {
            if (this.type === 'photo') return 'image/*';
            if (this.type === 'audio') return 'audio/*';
            if (this.type === 'video') return 'video/*';
            return '*/*';
        },
        handleFile(e) {
            this.fileName = e.target.files[0]?.name || '';
        },
        handleDrop(e) {
            this.dragging = false;
            const file = e.dataTransfer.files[0];
            if (!file) return;
            this.fileName = file.name;
            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.fileInput.files = dt.files;
        }
    }
}
</script>
@endsection
