@extends('layouts.app')

@section('title', 'New Client')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-1 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Clients
        </a>
        <h1 class="text-3xl font-bold text-white">New Client</h1>
        <p class="text-gray-400 mt-1">Add a client to assign to your projects</p>
    </div>

    <form action="{{ route('clients.store') }}" method="POST"
          class="bg-gray-900 border border-gray-800 rounded-2xl p-8 space-y-6">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Full Name <span class="text-red-400">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full bg-gray-800 border @error('name') border-red-500 @else border-gray-700 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                   placeholder="Jane Smith">
            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Company --}}
        <div>
            <label for="company" class="block text-sm font-medium text-gray-300 mb-1.5">Company</label>
            <input type="text" id="company" name="company" value="{{ old('company') }}"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                   placeholder="Acme Corp">
        </div>

        {{-- Email + Phone --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full bg-gray-800 border @error('email') border-red-500 @else border-gray-700 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                       placeholder="jane@example.com">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-1.5">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                       placeholder="+1 555 000 0000">
            </div>
        </div>

        {{-- Website --}}
        <div>
            <label for="website" class="block text-sm font-medium text-gray-300 mb-1.5">Website</label>
            <input type="url" id="website" name="website" value="{{ old('website') }}"
                   class="w-full bg-gray-800 border @error('website') border-red-500 @else border-gray-700 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition"
                   placeholder="https://example.com">
            @error('website') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Notes --}}
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-300 mb-1.5">Notes</label>
            <textarea id="notes" name="notes" rows="4"
                      class="w-full bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg px-4 py-3 text-white placeholder-gray-500 outline-none transition resize-none"
                      placeholder="Any notes about this client...">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-3 rounded-lg transition">
                Create Client
            </button>
            <a href="{{ route('clients.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-6 py-3 rounded-lg transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
