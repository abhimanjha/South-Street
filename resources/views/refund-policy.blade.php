@extends('layouts.app')

@section('title', 'Refund Policy - SouthStreet')

@section('content')
<style>
/* Disable card hover jump effect on refund policy page */
.card:hover {
    transform: none !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
}
</style>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">Refund Policy</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>Overview</h2>
                    <p>At SouthStreet, we strive to ensure your satisfaction with every purchase. If you're not completely happy with your order, our refund policy is designed to make the process as smooth as possible.</p>

                    <h3 class="mt-4">Eligibility for Refunds</h3>
                    <ul>
                        <li>Refunds are processed within 30 days of purchase.</li>
                        <li>Items must be returned in their original condition.</li>
                        <li>Custom tailored items are eligible for refund only if there's a manufacturing defect.</li>
                        <li>Original receipt or proof of purchase is required.</li>
                    </ul>

                    <h3 class="mt-4">How to Request a Refund</h3>
                    <ol>
                        <li>Contact our customer service team at <a href="{{ route('contact') }}">support@southstreet.com</a>.</li>
                        <li>Provide your order number and reason for the refund request.</li>
                        <li>We'll review your request and provide instructions for returning the item if applicable.</li>
                        <li>Once approved, refunds are processed within 5-7 business days.</li>
                    </ol>

                    <h3 class="mt-4">Refund Methods</h3>
                    <p>Refunds will be issued to the original payment method used for the purchase. Processing times may vary depending on your bank or payment provider.</p>

                    <h3 class="mt-4">Non-Refundable Items</h3>
                    <ul>
                        <li>Items damaged due to misuse or normal wear and tear.</li>
                        <li>Items returned after the 30-day window.</li>
                        <li>Items without original tags or packaging.</li>
                    </ul>

                    <h3 class="mt-4">Contact Us</h3>
                    <p>For any questions about our refund policy, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
