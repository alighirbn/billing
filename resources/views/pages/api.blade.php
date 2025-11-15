@extends('layouts.page')
@section('title', 'API Documentation - ' . config('app.name'))
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            API Documentation
        </h1>
        <p class="text-xl text-gray-400">
            Integrate {{ config('app.name') }} into your applications
        </p>
    </div>
<div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12 mb-8">
    <h2 class="text-3xl font-bold text-white mb-6">Getting Started with Our API</h2>
    
    <div class="prose prose-invert max-w-none text-gray-300">
        <p class="text-lg mb-6">
            The {{ config('app.name') }} API allows you to programmatically manage backups, access files, and integrate our backup solution into your applications.
        </p>

        <h3 class="text-2xl font-bold text-white mt-8 mb-4">Authentication</h3>
        <p>All API requests must be authenticated using an API key:</p>
        <div class="bg-black/50 rounded-xl p-6 my-6 font-mono text-sm overflow-x-auto">
            <pre class="text-green-400">curl -H "Authorization: Bearer YOUR_API_KEY" \
 https://api.example.com/v1/backups</pre>
        </div>

        <h3 class="text-2xl font-bold text-white mt-8 mb-4">Base URL</h3>
        <div class="bg-black/50 rounded-xl p-4 my-4 font-mono text-sm">
            <code class="text-purple-400">https://api.example.com/v1/</code>
        </div>

        <h3 class="text-2xl font-bold text-white mt-8 mb-4">Rate Limits</h3>
        <p>API requests are limited to:</p>
        <ul class="list-disc pl-6 space-y-2 my-4">
            <li>1000 requests per hour for authenticated requests</li>
            <li>100 requests per hour for unauthenticated requests</li>
        </ul>

        <h3 class="text-2xl font-bold text-white mt-8 mb-4">Available Endpoints</h3>
        
        <div class="space-y-4 mt-6">
            <div class="bg-white/5 border border-gray-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-bold text-white">List Backups</h4>
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">GET</span>
                </div>
                <code class="text-purple-400 text-sm">/v1/backups</code>
                <p class="text-gray-400 mt-3">Retrieve a list of all your backups</p>
            </div>

            <div class="bg-white/5 border border-gray-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-bold text-white">Create Backup</h4>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-semibold">POST</span>
                </div>
                <code class="text-purple-400 text-sm">/v1/backups</code>
                <p class="text-gray-400 mt-3">Initiate a new backup job</p>
            </div>

            <div class="bg-white/5 border border-gray-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-bold text-white">Get Backup Status</h4>
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">GET</span>
                </div>
                <code class="text-purple-400 text-sm">/v1/backups/:id</code>
                <p class="text-gray-400 mt-3">Check the status of a specific backup</p>
            </div>

            <div class="bg-white/5 border border-gray-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-bold text-white">Restore Files</h4>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-semibold">POST</span>
                </div>
                <code class="text-purple-400 text-sm">/v1/backups/:id/restore</code>
                <p class="text-gray-400 mt-3">Restore files from a backup</p>
            </div>
        </div>

        <h3 class="text-2xl font-bold text-white mt-12 mb-4">Example Request</h3>
        <div class="bg-black/50 rounded-xl p-6 my-6 font-mono text-sm overflow-x-auto">
            <pre class="text-green-400">curl -X POST https://api.example.com/v1/backups \
-H "Authorization: Bearer YOUR_API_KEY" 
-H "Content-Type: application/json" 
-d '{
"name": "Daily Backup",
"path": "/home/user/documents"
}'</pre>
</div>
        <h3 class="text-2xl font-bold text-white mt-8 mb-4">Need More Details?</h3>
        <p>For complete API reference documentation, code examples, and SDKs, visit our developer portal or contact our API support team.</p>

        <div class="flex gap-4 mt-8">
            <a href="mailto:api@example.com" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white">
                Contact API Support
            </a>
            <a href="#" class="inline-flex items-center px-6 py-3 bg-white/5 border border-gray-700 rounded-lg font-bold text-white hover:bg-white/10 transition-all">
                View Full Documentation
            </a>
        </div>
    </div>
</div>
</div>
@endsection