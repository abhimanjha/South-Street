@extends('layouts.app')

@section('title', 'Custom Tailoring')



@section('content')
<div class="custom-tailoring-page modern-tailoring-override">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-image">
                    <img src="{{ asset('imgs/ilu1.jpg') }}" alt="Custom Tailoring">
                </div>
                <div class="hero-text">
                    <h1 class="hero-title">South Street</h1>
                    <p class="hero-subtitle">Crafted with precision, tailored to perfection. Experience the luxury of custom-made clothing designed exclusively for you.</p>
                    <a href="#form" class="hero-cta">Start Your Custom Order</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Why Choose Our Custom Tailoring?</h2>
            <p class="section-subtitle">Experience the difference of personalized craftsmanship</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3 class="feature-title">Expert Craftsmanship</h3>
                    <p class="feature-description">Our skilled tailors bring decades of experience to create garments that fit you perfectly.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="feature-title">Premium Materials</h3>
                    <p class="feature-description">Choose from our curated selection of high-quality fabrics and materials.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <h3 class="feature-title">Perfect Fit</h3>
                    <p class="feature-description">Precise measurements and multiple fittings ensure your garment fits like a glove.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Timely Delivery</h3>
                    <p class="feature-description">We respect your time and deliver your custom pieces when promised.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="feature-title">Unique Design</h3>
                    <p class="feature-description">Create something truly unique that reflects your personal style and preferences.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Personal Service</h3>
                    <p class="feature-description">Dedicated consultation and support throughout your custom tailoring journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <h2 class="section-title">Our Tailoring Process</h2>
            <p class="section-subtitle">From concept to creation, we guide you through every step</p>
            
            <div class="process-grid">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="step-title">Consultation</h3>
                    <p class="step-description">We discuss your vision, style preferences, and requirements in detail.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-swatchbook"></i>
                    </div>
                    <h3 class="step-title">Material Selection</h3>
                    <p class="step-description">Choose from our premium fabric collection that suits your style and budget.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <h3 class="step-title">Measurements</h3>
                    <p class="step-description">Precise measurements are taken to ensure a perfect fit.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-scissors"></i>
                    </div>
                    <h3 class="step-title">Crafting</h3>
                    <p class="step-description">Our expert tailors carefully craft your garment with attention to detail.</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">5</div>
                    <div class="step-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="step-title">Delivery</h3>
                    <p class="step-description">Your perfectly tailored garment is delivered to you on time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Tailoring Form -->
    <section id="form" class="form-section">
        <div class="container">
            <div class="form-card">
                <h2 class="form-title">
                    <i class="fas fa-tshirt me-2"></i>Custom Tailoring Request Form
                </h2>
                
                <form action="{{ route('custom-tailoring.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="style_type" class="form-label">Style Type *</label>
                                <select class="form-control" id="style_type" name="style_type" required>
                                    <option value="">Select Style Type</option>
                                    <option value="Shirt">Shirt</option>
                                    <option value="Trousers">Trousers</option>
                                    <option value="Suit">Suit</option>
                                    <option value="Blazer">Blazer</option>
                                    <option value="Dress">Dress</option>
                                    <option value="Skirt">Skirt</option>
                                    <option value="Jacket">Jacket</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fabric" class="form-label">Preferred Fabric</label>
                                <input type="text" class="form-control" id="fabric" name="fabric" placeholder="e.g., Cotton, Silk, Wool">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" class="form-label">Preferred Color</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="e.g., Navy Blue, Black, White">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="size_details" class="form-label">Size Details *</label>
                        <textarea class="form-control" id="size_details" name="size_details" rows="4" placeholder="Please provide detailed measurements (chest, waist, length, etc.)" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="additional_notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" rows="4" placeholder="Any special requirements or preferences"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="reference_images" class="form-label">Reference Images</label>
                        <input type="file" class="form-control" id="reference_images" name="reference_images[]" multiple accept="image/*">
                        <small class="text-muted">Upload images of designs you like or reference photos (optional)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="budget" class="form-label">Budget Range</label>
                                <select class="form-control" id="budget" name="budget">
                                    <option value="">Select Budget Range</option>
                                    <option value="under-5000">Under ₹5,000</option>
                                    <option value="5000-10000">₹5,000 - ₹10,000</option>
                                    <option value="10000-20000">₹10,000 - ₹20,000</option>
                                    <option value="20000-50000">₹20,000 - ₹50,000</option>
                                    <option value="above-50000">Above ₹50,000</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="delivery_date" class="form-label">Preferred Delivery Date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
</div>
@endsection