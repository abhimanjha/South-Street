@extends('layouts.app')

@section('title', 'Contact Us - SouthStreet')

@section('content')
<div class="container-fluid px-4 py-5" style="max-width: 1200px; margin: 0 auto;">
    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-6">
            <h1 class="h2 fw-bold mb-4" style="color: #232f3e; font-family: 'Montserrat', sans-serif;">Get In Touch</h1>

            <div class="mb-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 mt-1">
                        <svg width="20" height="20" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                            <path d="M8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 1a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #232f3e;">Address</h6>
                        <p class="mb-0 text-muted small">123 Fashion Street, Mumbai, India</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 mt-1">
                        <svg width="20" height="20" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #232f3e;">Phone</h6>
                        <p class="mb-0 text-muted small">+91 98765 43210</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <div class="me-3 mt-1">
                        <svg width="20" height="20" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #232f3e;">Email</h6>
                        <p class="mb-0 text-muted small">support@southstreet.com</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="me-3 mt-1">
                        <svg width="20" height="20" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #232f3e;">Business Hours</h6>
                        <p class="mb-0 text-muted small">Mon - Sat: 9:00 AM - 8:00 PM</p>
                        <p class="mb-0 text-muted small">Sunday: 10:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Chat Button -->
            <a href="https://wa.me/919876543210?text=Hi%20SouthStreet,%20I%20need%20help%20with%20custom%20tailoring"
               target="_blank"
               class="btn d-inline-flex align-items-center px-4 py-2"
               style="background-color: #25d366; color: white; border: none; border-radius: 6px; text-decoration: none;">
                <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM8.734 14.605a6.507 6.507 0 0 1-3.098-.84l-.222-.125L2.6 14.293l.896-3.277-.209-.21a6.556 6.556 0 0 1-1.025-3.505c0-3.584 2.916-6.5 6.5-6.5a6.458 6.458 0 0 1 4.59 1.903 6.458 6.458 0 0 1 1.903 4.59c-.002 3.584-2.918 6.5-6.502 6.5z"/>
                    <path d="M11.908 8.922c-.204-.102-1.2-.594-1.386-.661-.186-.066-.321-.099-.455.099-.134.198-.549.661-.722.795-.173.134-.346.15-.55-.075-.204-.225-.862-.318-1.507-.985-.645-.667-1.078-1.486-1.204-1.738-.126-.252-.011-.387.094-.513.099-.12.22-.198.33-.297.11-.099.148-.165.223-.264.075-.099.099-.198.05-.297-.049-.099-.445-1.076-.612-1.464-.166-.389-.333-.334-.455-.41-.11-.075-.248-.086-.387-.055-.138.03-.254.11-.488.22-.234.11-.45.24-.72.395-.27.155-.514.34-.73.555-.215.215-.406.47-.56.755-.154.285-.27.59-.348.91-.078.32-.098.65-.08.98.018.33.09.645.21.94.12.295.27.57.45.82.18.25.39.48.62.68.23.2.48.37.74.52.26.15.53.27.81.36.29.09.59.13.89.12.21-.01.41-.03.61-.08.2-.05.39-.12.57-.21.18-.09.35-.2.51-.32.16-.12.31-.25.45-.39.14-.14.27-.29.39-.45.12-.16.23-.33.33-.51.1-.18.19-.37.27-.56.08-.19.14-.39.19-.59.05-.2.08-.4.08-.61 0-.21-.01-.42-.03-.63-.02-.21-.06-.41-.11-.61-.05-.2-.11-.4-.19-.59-.08-.19-.17-.37-.27-.55-.1-.18-.21-.35-.33-.51z"/>
                </svg>
                Chat on WhatsApp
            </a>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-6">
            <h2 class="h3 fw-bold mb-4" style="color: #232f3e; font-family: 'Montserrat', sans-serif;">Send us a Message</h2>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-0" style="background-color: #d4edda; color: #155724; border-left: 4px solid #28a745;">
                    <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="p-4 rounded-3" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small" style="color: #232f3e;">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-control form-control-sm border-0 rounded-0 @error('name') is-invalid @enderror"
                           style="background-color: white; padding: 12px 16px; font-size: 14px;"
                           placeholder="Enter your full name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold small" style="color: #232f3e;">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-sm border-0 rounded-0 @error('email') is-invalid @enderror"
                           style="background-color: white; padding: 12px 16px; font-size: 14px;"
                           placeholder="Enter your email address" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label fw-semibold small" style="color: #232f3e;">Subject</label>
                    <select id="subject" name="subject"
                            class="form-select form-select-sm border-0 rounded-0 @error('subject') is-invalid @enderror"
                            style="background-color: white; padding: 12px 16px; font-size: 14px;" required>
                        <option value="">Select a subject</option>
                        <option value="Custom Design Inquiry" {{ old('subject') == 'Custom Design Inquiry' ? 'selected' : '' }}>Custom Design Inquiry</option>
                        <option value="Measurement Help" {{ old('subject') == 'Measurement Help' ? 'selected' : '' }}>Measurement Help</option>
                        <option value="Order Support" {{ old('subject') == 'Order Support' ? 'selected' : '' }}>Order Support</option>
                        <option value="Style Consultation" {{ old('subject') == 'Style Consultation' ? 'selected' : '' }}>Style Consultation</option>
                        <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                    </select>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label fw-semibold small" style="color: #232f3e;">Message</label>
                    <textarea id="message" name="message" rows="5"
                              class="form-control border-0 rounded-0 @error('message') is-invalid @enderror"
                              style="background-color: white; padding: 12px 16px; font-size: 14px; resize: vertical;"
                              placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                        class="btn w-100 py-3 fw-semibold text-uppercase"
                        style="background-color: #ff9900; color: white; border: none; border-radius: 2px; font-size: 14px; letter-spacing: 0.5px;">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
