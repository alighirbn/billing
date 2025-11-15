@extends('layouts.page')
@section('title', 'Careers - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-6">
            Join Our Team
        </h1>
    <p class="text-xl text-gray-300 mb-8">
        We're building the future of cloud backup. Join us in our mission to make data protection simple and accessible for everyone.
    </p>

    <h2 class="text-2xl font-bold text-white mb-4">Why Work at {{ config('app.name') }}?</h2>
    
    <div class="grid md:grid-cols-2 gap-6 mb-12">
        <div class="bg-purple-500/10 border border-purple-500/20 rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-2">🚀 Fast-Growing Company</h3>
            <p class="text-gray-300">Be part of a rapidly expanding team with exciting challenges.</p>
        </div>
        
        <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-2">💰 Competitive Salary</h3>
            <p class="text-gray-300">We offer competitive compensation and equity packages.</p>
        </div>
        
        <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-2">🏡 Remote-First</h3>
            <p class="text-gray-300">Work from anywhere in the world with flexible hours.</p>
        </div>
        
        <div class="bg-orange-500/10 border border-orange-500/20 rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-2">📚 Learning Budget</h3>
            <p class="text-gray-300">Annual budget for courses, books, and conferences.</p>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-white mb-6">Open Positions</h2>
    
    <div class="space-y-4">
        <div class="bg-white/5 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Senior Backend Engineer</h3>
                    <p class="text-gray-300 mb-3">Build scalable infrastructure for millions of users</p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">Remote</span>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">Full-time</span>
                        <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Engineering</span>
                    </div>
                </div>
                <a href="mailto:careers@example.com" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-semibold text-white whitespace-nowrap">Apply Now</a>
            </div>
        </div>

        <div class="bg-white/5 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Product Designer</h3>
                    <p class="text-gray-300 mb-3">Create beautiful, intuitive user experiences</p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">Remote</span>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">Full-time</span>
                        <span class="px-3 py-1 bg-pink-500/20 text-pink-300 rounded-full text-sm">Design</span>
                    </div>
                </div>
                <a href="mailto:careers@example.com" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-semibold text-white whitespace-nowrap">Apply Now</a>
            </div>
        </div>

        <div class="bg-white/5 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Customer Success Manager</h3>
                    <p class="text-gray-300 mb-3">Help customers succeed with our platform</p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">Remote</span>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">Full-time</span>
                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-300 rounded-full text-sm">Support</span>
                    </div>
                </div>
                <a href="mailto:careers@example.com" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-semibold text-white whitespace-nowrap">Apply Now</a>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-purple-500/10 border border-purple-500/20 rounded-xl p-6">
        <h3 class="text-xl font-bold text-white mb-2">Don't see a fit?</h3>
        <p class="text-gray-300 mb-4">We're always looking for talented people. Send us your resume at <a href="mailto:careers@example.com" class="text-purple-400 hover:text-purple-300">careers@example.com</a></p>
    </div>
</div>
</div>
@endsection