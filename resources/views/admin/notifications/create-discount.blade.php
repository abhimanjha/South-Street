@extends('layouts.admin')

@section('title', 'Send Discount Notification')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Send Discount Notification</h1>

            <form action="{{ route('admin.notifications.send-discount') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Notification Title</label>
                    <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-2">Discount Code</label>
                    <input type="text" id="discount_code" name="discount_code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage (%)</label>
                    <input type="number" id="discount_percentage" name="discount_percentage" min="1" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-6">
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
