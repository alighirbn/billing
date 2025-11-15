@extends('layouts.page')
@section('title', 'Documentation - ' . config('app.name'))
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Documentation
        </h1>
        <p class="text-xl text-gray-400">
            Everything you need to know about using {{ config('app.name') }}
        </p>
    </div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Getting Started</h3>
        <p class="text-gray-400">Quick start guide to set up your account and first backup</p>
    </a>

    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Backup Guide</h3>
        <p class="text-gray-400">Learn how to backup files, folders, and entire systems</p>
    </a>

    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Restore Guide</h3>
        <p class="text-gray-400">How to restore your files quickly and easily</p>
    </a>

    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Account Settings</h3>
        <p class="text-gray-400">Manage your account, subscription, and preferences</p>
    </a>

    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Security</h3>
        <p class="text-gray-400">Understanding our security features and encryption</p>
    </a>

    <a href="#" class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6 hover:border-gray-700 transition-all group">
        <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">FAQ</h3>
        <p class="text-gray-400">Frequently asked questions and troubleshooting</p>
    </a>
</div>

<div class="mt-12 text-center bg-purple-500/10 border border-purple-500/20 rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-white mb-4">Need More Help?</h2>
    <p class="text-gray-300 mb-6">Can't find what you're looking for? Our support team is here to help.</p>
    <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white">
        Contact Support
        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
    </a>
</div>
</div>
@endsection