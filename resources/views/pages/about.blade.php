@extends('layouts.page')
@section('title', 'About Us - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-6">
            About {{ config('app.name') }}
        </h1>
        <div class="prose prose-invert max-w-none">
        <p class="text-xl text-gray-300 mb-6">
            {{ config('app.name') }} is a modern cloud backup solution designed to protect your most important data with enterprise-grade security and consumer-friendly simplicity.
        </p>

        <h2 class="text-2xl font-bold text-white mt-8 mb-4">Our Mission</h2>
        <p class="text-gray-300 mb-6">
            We believe everyone deserves access to reliable, secure cloud backup. Our mission is to make data protection effortless, affordable, and accessible to individuals and businesses of all sizes.
        </p>

        <h2 class="text-2xl font-bold text-white mt-8 mb-4">Why Choose Us?</h2>
        <ul class="space-y-3 text-gray-300 mb-6">
            <li class="flex items-start">
                <span class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">✓</span>
                <span><strong class="text-white">Military-grade encryption</strong> - Your data is encrypted both in transit and at rest</span>
            </li>
            <li class="flex items-start">
                <span class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">✓</span>
                <span><strong class="text-white">Lightning-fast restores</strong> - Access your files whenever you need them</span>
            </li>
            <li class="flex items-start">
                <span class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">✓</span>
                <span><strong class="text-white">Transparent pricing</strong> - No hidden fees, no surprises</span>
            </li>
            <li class="flex items-start">
                <span class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">✓</span>
                <span><strong class="text-white">24/7 support</strong> - Our team is always here to help</span>
            </li>
        </ul>

        <h2 class="text-2xl font-bold text-white mt-8 mb-4">Our Story</h2>
        <p class="text-gray-300 mb-6">
            Founded in {{ date('Y') - 2 }}, {{ config('app.name') }} was born from a simple frustration: existing backup solutions were either too complicated or too expensive. We set out to build something better - a service that just works, without the complexity.
        </p>

        <p class="text-gray-300 mb-6">
            Today, we're trusted by thousands of users worldwide to protect their precious memories, important documents, and critical business data.
        </p>

        <div class="bg-purple-500/10 border border-purple-500/20 rounded-xl p-6 mt-8">
            <h3 class="text-xl font-bold text-white mb-2">Ready to protect your data?</h3>
            <p class="text-gray-300 mb-4">Start your free trial today - no credit card required.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white">
                Get Started Free
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</div>
</div>
    </div>
</div>
@endsection