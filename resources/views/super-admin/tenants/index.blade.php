@extends('layouts.super-admin')

@section('header')
    <div class="flex justify-between items-center">
        <span>Tenants</span>
        <a href="{{ route('super-admin.tenants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Tenant
        </a>
    </div>
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        @forelse($tenants as $tenant)
            <li>
                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($tenant->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-indigo-600 truncate">{{ $tenant->name }}</p>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($tenant->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <p>{{ $tenant->primaryDomain?->getFullDomain() ?? 'No domain set' }}</p>
                                        <span class="mx-2">•</span>
                                        <p>{{ $tenant->plan }} plan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <div class="text-center">
                                    <p class="font-medium">{{ $tenant->domains_count }}</p>
                                    <p>Domains</p>
                                </div>
                                <div class="text-center">
                                    <p class="font-medium">{{ $tenant->users_count }}</p>
                                    <p>Users</p>
                                </div>
                                <div class="text-center">
                                    <p class="font-medium">{{ $tenant->products_count }}</p>
                                    <p>Products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="px-4 py-8 text-center text-gray-500">
                No tenants found. <a href="{{ route('super-admin.tenants.create') }}" class="text-blue-500 hover:underline">Create your first tenant</a>
            </li>
        @endforelse
    </ul>
</div>

@if($tenants->hasPages())
    <div class="mt-6">
        {{ $tenants->links() }}
    </div>
@endif
@endsection