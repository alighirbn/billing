@extends('layouts.page')
@section('title', 'Refund Policy - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Refund Policy
        </h1>
        <p class="text-gray-400 mb-8">Last updated: {{ date('F d, Y') }}</p>
    <div class="prose prose-invert max-w-none space-y-6 text-gray-300">
        <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-white mb-2">30-Day Money-Back Guarantee</h3>
            <p class="text-gray-300">We offer a full refund within 30 days of your initial purchase if you're not completely satisfied.</p>
        </div>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Eligibility for Refunds</h2>
            <p>You are eligible for a full refund if:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>You request a refund within 30 days of your initial purchase</li>
                <li>You are a first-time subscriber (refund only applies once per customer)</li>
                <li>The service does not meet your expectations</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">How to Request a Refund</h2>
            <ol class="list-decimal pl-6 space-y-3">
                <li>Contact our support team at <a href="mailto:support@example.com" class="text-purple-400 hover:text-purple-300">support@example.com</a></li>
                <li>Include your account email and reason for the refund</li>
                <li>We'll process your request within 2-3 business days</li>
                <li>Refunds typically appear in your account within 5-10 business days</li>
            </ol>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Subscription Cancellation</h2>
            <p>You can cancel your subscription at any time from your account dashboard. Upon cancellation:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Your subscription remains active until the end of the current billing period</li>
                <li>You will not be charged for the next billing cycle</li>
                <li>You retain access to your data for 30 days after cancellation</li>
                <li>No refunds are provided for partial months</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Non-Refundable Items</h2>
            <p>The following are not eligible for refunds:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Subscription renewals after the initial 30-day period</li>
                <li>Prorated refunds for mid-cycle cancellations</li>
                <li>Additional storage purchases</li>
                <li>Add-on services</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Exceptional Circumstances</h2>
            <p>We understand that special situations may arise. If you believe you have exceptional circumstances not covered by this policy, please contact our support team. We'll review your case individually and do our best to find a fair solution.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Questions?</h2>
            <p>If you have any questions about our refund policy, please don't hesitate to contact us:</p>
            <ul class="list-none space-y-2 mt-4">
                <li>📧 Email: <a href="mailto:support@example.com" class="text-purple-400 hover:text-purple-300">support@example.com</a></li>
                <li>💬 Live Chat: Available 24/7 in your dashboard</li>
            </ul>
        </section>
    </div>
</div>
</div>
@endsection