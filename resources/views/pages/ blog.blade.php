@extends('layouts.page')
@section('title', 'Blog - ' . config('app.name'))
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Blog & Updates
        </h1>
        <p class="text-xl text-gray-400">
            Latest news, tips, and insights from the {{ config('app.name') }} team
        </p>
    </div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Blog Post 1 -->
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all">
        <div class="w-full h-48 bg-gradient-to-br from-purple-600/20 to-blue-600/20 rounded-xl mb-4 flex items-center justify-center">
            <svg class="w-16 h-16 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <span class="text-sm text-purple-400 font-semibold">Security</span>
        <h3 class="text-xl font-bold text-white mt-2 mb-3">How We Keep Your Data Secure</h3>
        <p class="text-gray-400 mb-4">
            Learn about our multi-layer security approach including encryption, redundancy, and compliance standards.
        </p>
        <span class="text-sm text-gray-500">{{ date('M d, Y') }}</span>
    </div>

    <!-- Blog Post 2 -->
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all">
        <div class="w-full h-48 bg-gradient-to-br from-blue-600/20 to-cyan-600/20 rounded-xl mb-4 flex items-center justify-center">
            <svg class="w-16 h-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <span class="text-sm text-blue-400 font-semibold">Features</span>
        <h3 class="text-xl font-bold text-white mt-2 mb-3">5 Backup Best Practices</h3>
        <p class="text-gray-400 mb-4">
            Essential tips to ensure your data is always protected and quickly recoverable when you need it.
        </p>
        <span class="text-sm text-gray-500">{{ date('M d, Y', strtotime('-3 days')) }}</span>
    </div>

    <!-- Blog Post 3 -->
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all">
        <div class="w-full h-48 bg-gradient-to-br from-green-600/20 to-emerald-600/20 rounded-xl mb-4 flex items-center justify-center">
            <svg class="w-16 h-16 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="text-sm text-green-400 font-semibold">Success Stories</span>
        <h3 class="text-xl font-bold text-white mt-2 mb-3">Customer Success: Photo Studio</h3>
        <p class="text-gray-400 mb-4">
            How a professional photography studio uses {{ config('app.name') }} to protect thousands of client photos.
        </p>
        <span class="text-sm text-gray-500">{{ date('M d, Y', strtotime('-7 days')) }}</span>
    </div>
</div>

<div class="text-center mt-12">
    <p class="text-gray-400">More blog posts coming soon! Subscribe to our newsletter to stay updated.</p>
</div>
</div>
@endsection