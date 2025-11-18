@extends('layouts.app')

@section('title', 'Privacy Policy - SouthStreet')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">Privacy Policy</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>1. Information We Collect</h2>
                    <p>We collect personal information you provide to us, such as name, email, and shipping address.</p>

                    <h2>2. How We Use Your Information</h2>
                    <p>We use your information to process orders, provide customer service, and improve our services.</p>

                    <h2>3. Information Sharing</h2>
                    <p>We do not sell or rent your personal information to third parties.</p>

                    <h2>4. Data Security</h2>
                    <p>We implement appropriate security measures to protect your personal information.</p>

                    <h2>5. Your Rights</h2>
                    <p>You have the right to access, update, or delete your personal information.</p>

                    <h2>6. Contact Us</h2>
                    <p>If you have any questions, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
