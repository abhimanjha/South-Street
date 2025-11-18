@extends('layouts.app')

@section('title', 'Custom Tailoring Request')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">Custom Tailoring Request</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('custom-tailoring.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Cloth Material -->
                    <div>
                        <label for="cloth_material" class="block text-sm font-medium text-gray-700 mb-2">Cloth Material *</label>
                        <select id="cloth_material" name="cloth_material" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Material</option>
                            <option value="cotton" {{ old('cloth_material') == 'cotton' ? 'selected' : '' }}>Cotton</option>
                            <option value="silk" {{ old('cloth_material') == 'silk' ? 'selected' : '' }}>Silk</option>
                            <option value="wool" {{ old('cloth_material') == 'wool' ? 'selected' : '' }}>Wool</option>
                            <option value="linen" {{ old('cloth_material') == 'linen' ? 'selected' : '' }}>Linen</option>
                            <option value="polyester" {{ old('cloth_material') == 'polyester' ? 'selected' : '' }}>Polyester</option>
                            <option value="other" {{ old('cloth_material') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('cloth_material') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Preferred Color *</label>
                        <input type="text" id="color" name="color" value="{{ old('color') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Style -->
                    <div>
                        <label for="style" class="block text-sm font-medium text-gray-700 mb-2">Style/Design *</label>
                        <input type="text" id="style" name="style" value="{{ old('style') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('style') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Sizes -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Measurements (in inches) *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label for="chest" class="block text-xs text-gray-600">Chest</label>
                            <input type="number" step="0.1" id="chest" name="sizes[chest]" value="{{ old('sizes.chest') }}" required
                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                        </div>
                        <div>
                            <label for="waist" class="block text-xs text-gray-600">Waist</label>
                            <input type="number" step="0.1" id="waist" name="sizes[waist]" value="{{ old('sizes.waist') }}" required
                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                        </div>
                        <div>
                            <label for="hips" class="block text-xs text-gray-600">Hips</label>
                            <input type="number" step="0.1" id="hips" name="sizes[hips]" value="{{ old('sizes.hips') }}" required
                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                        </div>
                        <div>
                            <label for="length" class="block text-xs text-gray-600">Length</label>
                            <input type="number" step="0.1" id="length" name="sizes[length]" value="{{ old('sizes.length') }}" required
                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                        </div>
                    </div>
                    @error('sizes') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Any special requirements or additional details...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Submit Custom Tailoring Request
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
@endsection
