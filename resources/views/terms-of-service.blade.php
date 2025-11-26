@extends('layouts.app')

@section('title', 'Terms & Conditions - SouthStreet')

@section('content')
<style>
/* Disable card hover jump effect on terms page */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">Terms & Conditions</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>1. Introduction</h2>
                    <p>Welcome to SouthStreet. These terms and conditions outline the rules and regulations for the use of our website and services.</p>

                    <h2>2. Intellectual Property Rights</h2>
                    <p>All content on this website is the property of SouthStreet and is protected by copyright laws.</p>

                    <h2>3. User Responsibilities</h2>
                    <p>Users must provide accurate information and use the site in accordance with applicable laws.</p>

                    <h2>4. Limitation of Liability</h2>
                    <p>SouthStreet shall not be liable for any indirect, incidental, or consequential damages.</p>

                    <h2>5. Governing Law</h2>
                    <p>These terms are governed by the laws of [Your Jurisdiction].</p>

                    <h2>6. Contact Information</h2>
                    <p>If you have any questions, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
