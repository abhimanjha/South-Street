@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Hero Section -->
        <div class="about-hero mb-5">
            <h1 class="display-4 fw-bold mb-3">About Us</h1>
            <p class="lead">Welcome to our store! We specialize in premium products and thoughtful design.</p>
        </div>

        <!-- Our Story Section -->
        <div class="row mb-5">
            <div class="col-lg-6">
                <h2 class="mb-4">Our Story</h2>
                <p>Founded with a passion for excellence, our journey began with a simple belief: everyone deserves access to quality products that enhance their daily lives. What started as a small venture has grown into a trusted destination for discerning customers who value craftsmanship and authenticity.</p>
                <p>Over the years, we've built lasting relationships with artisans, designers, and manufacturers who share our commitment to quality. Every product in our collection is carefully selected to meet our high standards.</p>
            </div>
            <div class="col-lg-6">
                <img src="/images/our-story.jpg" alt="Our Story" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- Our Mission Section -->
        <div class="bg-light p-5 rounded mb-5">
            <h2 class="mb-4 text-center">Our Mission</h2>
            <p class="text-center lead mb-0">To deliver exceptional products that combine timeless design with modern functionality, while providing an unparalleled shopping experience that exceeds expectations at every touchpoint.</p>
        </div>

        <!-- Our Values Section -->
        <div class="mb-5">
            <h2 class="mb-4 text-center">Our Values</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-check fs-1 text-primary"></i>
                        </div>
                        <h4>Quality First</h4>
                        <p>We never compromise on quality. Each product undergoes rigorous selection to ensure it meets our exacting standards.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-heart fs-1 text-primary"></i>
                        </div>
                        <h4>Customer Centric</h4>
                        <p>Your satisfaction drives everything we do. We're committed to providing personalized service and support.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-recycle fs-1 text-primary"></i>
                        </div>
                        <h4>Sustainability</h4>
                        <p>We're dedicated to sustainable practices, from sourcing to packaging, to minimize our environmental impact.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="row mb-5">
            <div class="col-lg-6 order-lg-2">
                <h2 class="mb-4">Why Choose Us</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <strong>Curated Selection:</strong> Every product is handpicked for quality and design excellence.
                    </li>
                    <li class="mb-3">
                        <strong>Exceptional Service:</strong> Our dedicated team is here to assist you at every step.
                    </li>
                    <li class="mb-3">
                        <strong>Secure Shopping:</strong> Your privacy and security are our top priorities.
                    </li>
                    <li class="mb-3">
                        <strong>Fast Shipping:</strong> We ensure your orders arrive quickly and safely.
                    </li>
                    <li class="mb-3">
                        <strong>Easy Returns:</strong> Not satisfied? Our hassle-free return policy has you covered.
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 order-lg-1">
                <img src="/images/why-choose-us.jpg" alt="Why Choose Us" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- Team Section -->
        <div class="mb-5">
            <h2 class="mb-4 text-center">Meet Our Team</h2>
            <p class="text-center mb-5">The passionate individuals behind our success</p>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <img src="/images/team-1.jpg" alt="Team Member" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Jane Smith</h5>
                        <p class="text-muted">Founder & CEO</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <img src="/images/team-2.jpg" alt="Team Member" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Michael Chen</h5>
                        <p class="text-muted">Head of Design</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <img src="/images/team-3.jpg" alt="Team Member" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Sarah Johnson</h5>
                        <p class="text-muted">Customer Success</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <img src="/images/team-4.jpg" alt="Team Member" class="rounded-circle mb-3" width="150" height="150">
                        <h5>David Martinez</h5>
                        <p class="text-muted">Operations Manager</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="bg-light p-5 rounded mb-5">
            <h2 class="mb-4 text-center">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="mb-3">"Absolutely love the quality and attention to detail. Best purchase I've made this year!"</p>
                            <p class="text-muted mb-0"><strong>- Emma R.</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="mb-3">"Exceptional customer service and fast delivery. Highly recommend!"</p>
                            <p class="text-muted mb-0"><strong>- James T.</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="mb-3">"The products exceeded my expectations. Will definitely shop here again!"</p>
                            <p class="text-muted mb-0"><strong>- Lisa M.</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center py-5">
            <h2 class="mb-4">Ready to Experience the Difference?</h2>
            <p class="lead mb-4">Join thousands of satisfied customers who trust us for their shopping needs.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-2">Shop Now</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">Contact Us</a>
        </div>
    </div>
@endsection