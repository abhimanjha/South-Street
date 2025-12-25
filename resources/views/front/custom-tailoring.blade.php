/;@extends('layouts.app')

@section('title', 'Custom Tailoring')



@section('content')
<script>
document.body.classList.add('custom-tailoring-page');
</script>
<div class="custom-tailoring-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">South Street</h1>
                <p class="hero-subtitle">Crafted with precision, tailored to perfection. Experience the luxury of custom-made clothing designed exclusively for you.</p>
                <a href="#form" class="hero-cta">Start Your Custom Order</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Why Choose Custom Tailoring?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <h3 class="feature-title">Perfect Fit</h3>
                    <p class="feature-description">Every garment is crafted to your exact measurements, ensuring a perfect fit that enhances your silhouette and comfort.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="feature-title">Unlimited Customization</h3>
                    <p class="feature-description">Choose from premium fabrics, colors, and styles. Create a unique piece that reflects your personal taste and style.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-description">We use only the finest materials and employ skilled craftsmen to ensure exceptional quality and durability.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Expert Craftsmanship</h3>
                    <p class="feature-description">Our experienced tailors bring decades of expertise to create garments that meet the highest standards of excellence.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Personal Service</h3>
                    <p class="feature-description">Enjoy personalized consultation and dedicated support throughout your custom tailoring journey.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Quality Guarantee</h3>
                    <p class="feature-description">We stand behind our work with a satisfaction guarantee and complimentary alterations if needed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <h2 class="section-title">Our Tailoring Process</h2>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Consultation</h3>
                    <p class="step-description">Share your vision, preferences, and requirements. We'll guide you through fabric and style options.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Measurements</h3>
                    <p class="step-description">Precise measurements are taken to ensure the perfect fit for your unique body shape.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Design & Cut</h3>
                    <p class="step-description">Our master tailors create patterns and cut the fabric with meticulous attention to detail.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Crafting</h3>
                    <p class="step-description">Skilled artisans hand-craft your garment using traditional techniques and modern precision.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">5</div>
                    <h3 class="step-title">Final Fitting</h3>
                    <p class="step-description">Final adjustments are made to ensure perfect fit and your complete satisfaction.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="form-section" id="form">
        <div class="container">
            <div class="form-container">
                <h2 class="form-title">Start Your Custom Order</h2>
                
                <form action="{{ route('custom-tailoring.store') }}" method="POST">
                    @csrf

                    <!-- Personal Information -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="form-control">
                            @error('name') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="form-control">
                            @error('email') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="form-control">
                        @error('phone') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <!-- Garment Details -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cloth_material" class="form-label">Fabric Material *</label>
                            <select id="cloth_material" name="cloth_material" required class="form-control">
                                <option value="">Select Fabric</option>
                                <option value="cotton" {{ old('cloth_material') == 'cotton' ? 'selected' : '' }}>Premium Cotton</option>
                                <option value="silk" {{ old('cloth_material') == 'silk' ? 'selected' : '' }}>Pure Silk</option>
                                <option value="wool" {{ old('cloth_material') == 'wool' ? 'selected' : '' }}>Fine Wool</option>
                                <option value="linen" {{ old('cloth_material') == 'linen' ? 'selected' : '' }}>Luxury Linen</option>
                                <option value="polyester" {{ old('cloth_material') == 'polyester' ? 'selected' : '' }}>High-Quality Polyester</option>
                                <option value="cashmere" {{ old('cloth_material') == 'cashmere' ? 'selected' : '' }}>Cashmere</option>
                                <option value="other" {{ old('cloth_material') == 'other' ? 'selected' : '' }}>Other (Specify in notes)</option>
                            </select>
                            @error('cloth_material') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="color" class="form-label">Preferred Color *</label>
                            <input type="text" id="color" name="color" value="{{ old('color') }}" required class="form-control" placeholder="e.g., Navy Blue, Charcoal Gray">
                            @error('color') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="style" class="form-label">Style/Design *</label>
                        <input type="text" id="style" name="style" value="{{ old('style') }}" required class="form-control" placeholder="e.g., Slim Fit Suit, A-Line Dress, Casual Shirt">
                        @error('style') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <!-- Measurements -->
                    <div class="form-group">
                        <label class="form-label">Measurements (in inches) *</label>
                        <div class="measurements-grid">
                            <div>
                                <label for="chest" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Chest</label>
                                <input type="number" step="0.1" id="chest" name="sizes[chest]" value="{{ old('sizes.chest') }}" required class="form-control">
                            </div>
                            <div>
                                <label for="waist" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Waist</label>
                                <input type="number" step="0.1" id="waist" name="sizes[waist]" value="{{ old('sizes.waist') }}" required class="form-control">
                            </div>
                            <div>
                                <label for="hips" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Hips</label>
                                <input type="number" step="0.1" id="hips" name="sizes[hips]" value="{{ old('sizes.hips') }}" required class="form-control">
                            </div>
                            <div>
                                <label for="length" class="form-label" style="font-size: 0.85rem; margin-bottom: 0.25rem;">Length</label>
                                <input type="number" step="0.1" id="length" name="sizes[length]" value="{{ old('sizes.length') }}" required class="form-control">
                            </div>
                        </div>
                        @error('sizes') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="notes" class="form-label">Additional Requirements</label>
                        <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Any special requirements, design preferences, or additional details...">{{ old('notes') }}</textarea>
                        @error('notes') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane me-2"></i>Submit Custom Order Request
                    </button>
                </form>

                @if(session('success'))
                    <div class="success-alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
