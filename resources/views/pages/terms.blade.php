@extends('layouts.page')
@section('title', 'Terms of Service - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Terms of Service
        </h1>
        <p class="text-gray-400 mb-8">Last updated: {{ date('F d, Y') }}</p>
    <div class="prose prose-invert max-w-none space-y-6 text-gray-300">
        <section>
            <h2 class="text-2xl font-bold text-white mb-4">1. Acceptance of Terms</h2>
            <p>By accessing and using {{ config('app.name') }}, you accept and agree to be bound by the terms and provision of this agreement.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">2. Use License</h2>
            <p>We grant you a limited, non-exclusive, non-transferable license to use our service for your personal or business backup needs, subject to these Terms.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">3. User Responsibilities</h2>
            <p>You are responsible for:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Maintaining the confidentiality of your account credentials</li>
                <li>All activities that occur under your account</li>
                <li>Ensuring your use complies with applicable laws</li>
                <li>The content you upload and backup</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">4. Prohibited Uses</h2>
            <p>You may not use our service to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Store or transmit illegal content</li>
                <li>Violate any intellectual property rights</li>
                <li>Transmit malware or malicious code</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Interfere with or disrupt our services</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">5. Payment Terms</h2>
            <p>Subscription fees are billed in advance on a monthly or annual basis. You can cancel your subscription at any time. Refunds are provided according to our Refund Policy.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">6. Service Availability</h2>
            <p>While we strive for 99.9% uptime, we do not guarantee uninterrupted access to our services. We reserve the right to modify or discontinue services with reasonable notice.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">7. Limitation of Liability</h2>
            <p>{{ config('app.name') }} shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use or inability to use the service.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">8. Changes to Terms</h2>
            <p>We reserve the right to modify these terms at any time. We will notify users of any material changes via email or through the service.</p>
        </section>
    </div>
</div>
</div>
@endsection