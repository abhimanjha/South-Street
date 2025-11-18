@extends('layouts.app')

@section('title', 'Shipping Policy - SouthStreet')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">Shipping Policy</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>Overview</h2>
                    <p>At SouthStreet, we are committed to delivering your orders quickly and safely. Our shipping policy ensures you receive your purchases in the best condition possible.</p>

                    <h3 class="mt-4">Processing Time</h3>
                    <ul>
                        <li>Standard orders are processed within 1-2 business days.</li>
                        <li>Custom tailoring orders may take 3-5 business days for processing.</li>
                        <li>Orders placed on weekends or holidays will be processed on the next business day.</li>
                    </ul>

                    <h3 class="mt-4">Shipping Methods</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th>Delivery Time</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Standard Shipping</td>
                                    <td>5-7 business days</td>
                                    <td>₹499</td>
                                </tr>
                                <tr>
                                    <td>Express Shipping</td>
                                    <td>2-3 business days</td>
                                    <td>₹999</td>
                                </tr>
                                <tr>
                                    <td>Overnight Shipping</td>
                                    <td>1 business day</td>
                                    <td>₹1999</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="mt-4">Shipping Costs</h3>
                    <ul>
                        <li>Free shipping on orders over ₹5000.</li>
                        <li>Additional charges may apply for oversized or heavy items.</li>
                        <li>International shipping rates vary by destination.</li>
                    </ul>

                    <h3 class="mt-4">International Shipping</h3>
                    <p>We ship to most countries worldwide. International orders may be subject to customs duties and taxes, which are the responsibility of the recipient.</p>

                    <h3 class="mt-4">Tracking Your Order</h3>
                    <p>Once your order ships, you'll receive a tracking number via email. You can track your package on our website or the carrier's site.</p>

                    <h3 class="mt-4">Delivery Issues</h3>
                    <p>If you experience any issues with delivery, please contact us immediately at <a href="{{ route('contact') }}">support@southstreet.com</a>.</p>

                    <h3 class="mt-4">Contact Us</h3>
                    <p>For shipping-related questions, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
