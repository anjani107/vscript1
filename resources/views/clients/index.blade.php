@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white">Clients</h1>
        <p class="text-gray-400 mt-1">Manage your client relationships</p>
    </div>
    <a href="{{ route('clients.create') }}"
       class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-2.5 rounded-lg transition">
        + New Client
    </a>
</div>

@if ($clients->isEmpty())
    <div class="text-center py-24 border-2 border-dashed border-gray-700 rounded-2xl">
        <svg class="mx-auto w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="text-gray-400 text-lg mb-4">No clients yet</p>
        <a href="{{ route('clients.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-3 rounded-lg transition">
            Add your first client
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($clients as $client)
            <a href="{{ route('clients.show', $client) }}"
               class="group bg-gray-900 border border-gray-800 hover:border-indigo-500 rounded-2xl p-6 flex flex-col gap-4 transition">

                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-12 h-12 rounded-xl bg-indigo-700 flex items-center justify-center text-lg font-bold text-white shrink-0">
                        {{ $client->initials }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-white group-hover:text-indigo-400 transition truncate">
                            {{ $client->name }}
                        </h2>
                        @if ($client->company)
                            <p class="text-sm text-gray-400 truncate">{{ $client->company }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-1.5 text-sm text-gray-400">
                    @if ($client->email)
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="truncate">{{ $client->email }}</span>
                        </div>
                    @endif
                    @if ($client->phone)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $client->phone }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-auto flex items-center justify-between pt-2 border-t border-gray-800 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                        </svg>
                        {{ $client->video_projects_count }} {{ Str::plural('project', $client->video_projects_count) }}
                    </span>
                    <span>Added {{ $client->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
