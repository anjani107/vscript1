@extends('layouts.app')

@section('title', $client->name)

@section('content')

{{-- Header --}}
<div class="mb-8">
    <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-1 transition mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        All Clients
    </a>

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-700 flex items-center justify-center text-2xl font-bold text-white shrink-0">
                {{ $client->initials }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $client->name }}</h1>
                @if ($client->company)
                    <p class="text-gray-400 mt-0.5">{{ $client->company }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('clients.edit', $client) }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium px-4 py-2 rounded-lg border border-gray-700 transition">
                Edit
            </a>
            <form method="POST" action="{{ route('clients.destroy', $client) }}"
                  onsubmit="return confirm('Delete {{ $client->name }}? Their projects will not be deleted.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="bg-red-900/50 hover:bg-red-800 text-red-300 text-sm font-medium px-4 py-2 rounded-lg border border-red-800 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- LEFT: Contact Info --}}
    <div class="space-y-5">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 space-y-4">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Contact Details</h2>

            @if ($client->email)
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Email</p>
                        <a href="mailto:{{ $client->email }}" class="text-indigo-400 hover:text-indigo-300 text-sm transition">{{ $client->email }}</a>
                    </div>
                </div>
            @endif

            @if ($client->phone)
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Phone</p>
                        <a href="tel:{{ $client->phone }}" class="text-sm text-gray-300 hover:text-white transition">{{ $client->phone }}</a>
                    </div>
                </div>
            @endif

            @if ($client->website)
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Website</p>
                        <a href="{{ $client->website }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 text-sm transition break-all">
                            {{ parse_url($client->website, PHP_URL_HOST) ?: $client->website }}
                        </a>
                    </div>
                </div>
            @endif

            @if (!$client->email && !$client->phone && !$client->website)
                <p class="text-gray-600 text-sm italic">No contact details added.</p>
            @endif
        </div>

        @if ($client->notes)
            <div class="bg-yellow-950 border border-yellow-800 rounded-2xl p-6">
                <h2 class="text-sm font-semibold text-yellow-400 uppercase tracking-wider mb-3">Notes</h2>
                <p class="text-yellow-200/80 text-sm leading-relaxed whitespace-pre-wrap">{{ $client->notes }}</p>
            </div>
        @endif

        <div class="text-xs text-gray-600 text-center">
            Client since {{ $client->created_at->format('M j, Y') }}
        </div>
    </div>

    {{-- RIGHT: Projects --}}
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                Projects
                <span class="text-sm text-gray-500 font-normal">({{ $client->videoProjects->count() }})</span>
            </h2>
            <a href="{{ route('projects.create') }}?client_id={{ $client->id }}"
               class="text-sm bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition">
                + New Project
            </a>
        </div>

        @forelse ($client->videoProjects as $project)
            @php
                $statusColors = ['draft' => 'gray', 'in_progress' => 'yellow', 'completed' => 'green'];
                $statusLabels = ['draft' => 'Draft', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
                $color = $statusColors[$project->status];
            @endphp
            <a href="{{ route('projects.show', $project) }}"
               class="group flex items-center justify-between bg-gray-900 border border-gray-800 hover:border-indigo-500 rounded-xl px-5 py-4 mb-3 transition">
                <div class="min-w-0">
                    <h3 class="font-medium text-white group-hover:text-indigo-400 transition truncate">{{ $project->title }}</h3>
                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                        <span>{{ $project->scripts_count }} scripts</span>
                        <span>{{ $project->assets_count }} assets</span>
                        <span>{{ $project->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                <span class="ml-4 shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full
                    @if($color === 'green') bg-green-900 text-green-300
                    @elseif($color === 'yellow') bg-yellow-900 text-yellow-300
                    @else bg-gray-800 text-gray-400 @endif">
                    {{ $statusLabels[$project->status] }}
                </span>
            </a>
        @empty
            <div class="bg-gray-900 border border-gray-800 border-dashed rounded-2xl p-10 text-center">
                <p class="text-gray-500 mb-3">No projects for this client yet.</p>
                <a href="{{ route('projects.create') }}?client_id={{ $client->id }}"
                   class="text-sm text-indigo-400 hover:text-indigo-300 transition">Create a project →</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
