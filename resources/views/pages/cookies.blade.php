@extends('layouts.page')
@section('title', 'Cookie Policy - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Cookie Policy
        </h1>
        <p class="text-gray-400 mb-8">Last updated: {{ date('F d, Y') }}</p>
    <div class="prose prose-invert max-w-none space-y-6 text-gray-300">
        <section>
            <h2 class="text-2xl font-bold text-white mb-4">What Are Cookies?</h2>
            <p>Cookies are small text files that are stored on your device when you visit our website. They help us provide you with a better experience by remembering your preferences and understanding how you use our service.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Types of Cookies We Use</h2>
            
            <h3 class="text-xl font-semibold text-white mt-6 mb-3">Essential Cookies</h3>
            <p>These cookies are necessary for the website to function and cannot be switched off. They include:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Authentication cookies to keep you logged in</li>
                <li>Security cookies to protect against fraud</li>
                <li>Session cookies for basic functionality</li>
            </ul>

            <h3 class="text-xl font-semibold text-white mt-6 mb-3">Analytics Cookies</h3>
            <p>These help us understand how visitors interact with our website:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Page visit tracking</li>
                <li>Feature usage statistics</li>
                <li>Error reporting</li>
            </ul>

            <h3 class="text-xl font-semibold text-white mt-6 mb-3">Preference Cookies</h3>
            <p>These remember your choices and provide enhanced features:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Language preferences</li>
                <li>Theme settings</li>
                <li>Region/timezone</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Managing Cookies</h2>
            <p>You can control cookies through your browser settings. However, disabling certain cookies may limit your ability to use some features of our service.</p>
            <p class="mt-4">Most browsers allow you to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>View what cookies are stored and delete them individually</li>
                <li>Block third-party cookies</li>
                <li>Block cookies from particular sites</li>
                <li>Block all cookies from being set</li>
                <li>Delete all cookies when you close your browser</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Third-Party Cookies</h2>
            <p>We use some third-party services that may set cookies:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Payment processors (Paddle)</li>
                <li>Analytics providers (if applicable)</li>
                <li>Support chat services</li>
            </ul>
            <p class="mt-4">These services have their own privacy policies governing their use of cookies.</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">Updates to This Policy</h2>
            <p>We may update this Cookie Policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
        </section>
    </div>
</div>
</div>
@endsection