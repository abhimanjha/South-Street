@extends('layouts.app')

@section('title', 'Returns & Exchange Policy - SouthStreet')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">Returns & Exchange Policy</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>Overview</h2>
                    <p>At SouthStreet, we want you to be completely satisfied with your purchase. If you're not happy with your order, we're here to help with our hassle-free returns and exchange policy.</p>

                    <h3 class="mt-4">Eligibility for Returns</h3>
                    <ul>
                        <li>Items must be returned within 30 days of delivery.</li>
                        <li>Items must be in their original condition, unworn, and with all tags attached.</li>
                        <li>Custom tailored items are not eligible for return unless there's a manufacturing defect.</li>
                        <li>Proof of purchase is required.</li>
                    </ul>

                    <h3 class="mt-4">How to Return an Item</h3>
                    <ol>
                        <li>Contact our customer support team at <a href="{{ route('contact') }}">support@southstreet.com</a> or call us.</li>
                        <li>Provide your order number and reason for return.</li>
                        <li>We'll provide you with a return authorization number and shipping instructions.</li>
                        <li>Pack the item securely and ship it back to us using the provided label.</li>
                    </ol>

                    <h3 class="mt-4">Exchanges</h3>
                    <p>We offer exchanges for a different size, color, or style within 30 days of delivery. Exchanges are subject to availability.</p>

                    <h3 class="mt-4">Refunds</h3>
                    <p>Once we receive and inspect your return, we'll process your refund within 5-7 business days. Refunds will be issued to the original payment method.</p>

                    <h3 class="mt-4">Shipping Costs</h3>
                    <p>Return shipping costs are the responsibility of the customer unless the item is defective or we made an error.</p>

                    <h3 class="mt-4">Contact Us</h3>
                    <p>If you have any questions about our returns policy, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
