@extends('layouts.app')

@section('title', 'Custom Tailoring Request')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="text-center mb-4">Custom Tailoring Request</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('tailoring.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                       class="form-control">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                       class="form-control">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                       class="form-control">
                                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Cloth Material -->
                            <div class="col-md-6">
                                <label for="cloth_material" class="form-label">Cloth Material *</label>
                                <select id="cloth_material" name="cloth_material" required class="form-select">
                                    <option value="">Select Material</option>
                                    <option value="cotton" {{ old('cloth_material') == 'cotton' ? 'selected' : '' }}>Cotton</option>
                                    <option value="silk" {{ old('cloth_material') == 'silk' ? 'selected' : '' }}>Silk</option>
                                    <option value="wool" {{ old('cloth_material') == 'wool' ? 'selected' : '' }}>Wool</option>
                                    <option value="linen" {{ old('cloth_material') == 'linen' ? 'selected' : '' }}>Linen</option>
                                    <option value="polyester" {{ old('cloth_material') == 'polyester' ? 'selected' : '' }}>Polyester</option>
                                    <option value="other" {{ old('cloth_material') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('cloth_material') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Color -->
                            <div class="col-md-6">
                                <label for="color" class="form-label">Preferred Color *</label>
                                <input type="text" id="color" name="color" value="{{ old('color') }}" required
                                       class="form-control">
                                @error('color') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- Style Type -->
                            <div class="col-md-6">
                                <label for="style_type" class="form-label">Style/Design *</label>
                                <input type="text" id="style_type" name="style_type" value="{{ old('style_type') }}" required
                                       class="form-control">
                                @error('style_type') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Size Details -->
                        <div class="mb-3">
                            <label for="size_details" class="form-label">Size Details *</label>
                            <textarea id="size_details" name="size_details" rows="4" required
                                      class="form-control"
                                      placeholder="Please provide detailed measurements (chest, waist, hips, length, etc.)">{{ old('size_details') }}</textarea>
                            @error('size_details') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea id="additional_notes" name="additional_notes" rows="4"
                                      class="form-control"
                                      placeholder="Any special requirements or additional details...">{{ old('additional_notes') }}</textarea>
                            @error('additional_notes') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Tailoring Request</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success mt-3" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
