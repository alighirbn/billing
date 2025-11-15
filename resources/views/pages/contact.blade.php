@extends('layouts.page')
@section('title', 'Contact Us - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-6">
            Get in Touch
        </h1>
    <p class="text-xl text-gray-300 mb-8">
        Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
    </p>

    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <div>
            <h2 class="text-2xl font-bold text-white mb-6">Contact Information</h2>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-1">Email</h3>
                        <a href="mailto:support@example.com" class="text-purple-400 hover:text-purple-300">support@example.com</a>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-1">Live Chat</h3>
                        <p class="text-gray-300">Available 24/7</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-1">Office</h3>
                        <p class="text-gray-300">123 Cloud Street<br>San Francisco, CA 94102</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-white mb-6">Send us a Message</h2>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Name</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/5 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none" placeholder="Your name">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-3 bg-white/5 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none" placeholder="your@email.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Message</label>
                    <textarea rows="4" class="w-full px-4 py-3 bg-white/5 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none" placeholder="How can we help?"></textarea>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white hover:shadow-lg transition-all">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection