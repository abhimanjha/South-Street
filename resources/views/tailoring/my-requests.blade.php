@extends('layouts.app')

@section('title', 'My Tailoring Requests')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">My Tailoring Requests</h1>

        @if($requests->count() > 0)
            <div class="space-y-6">
                @foreach($requests as $request)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $request->cloth_material }} - {{ $request->style_type }}</h3>
                                <p class="text-gray-600">Submitted on {{ $request->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($request->status == 'Pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status == 'Approved') bg-green-100 text-green-800
                                @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                @elseif($request->status == 'In Progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $request->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600"><strong>Color:</strong> {{ $request->color }}</p>
                                <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ $request->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600"><strong>Size Details:</strong></p>
                                <p class="text-sm text-gray-800">{{ $request->size_details }}</p>
                            </div>
                        </div>

                        @if($request->additional_notes)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600"><strong>Additional Notes:</strong></p>
                                <p class="text-sm text-gray-800">{{ $request->additional_notes }}</p>
                            </div>
                        @endif

                        @if($request->updated_at != $request->created_at)
                            <p class="text-xs text-gray-500">Last updated: {{ $request->updated_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No tailoring requests yet</h3>
                <p class="text-gray-500 mb-6">You haven't submitted any tailoring requests yet.</p>
                <a href="{{ route('tailoring.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Submit Your First Request
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
