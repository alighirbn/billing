@extends('layouts.page')
@section('title', 'Privacy Policy - ' . config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-900/50 backdrop-blur border border-gray-800 rounded-3xl p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
            Privacy Policy
        </h1>
        <p class="text-gray-400 mb-8">Last updated: {{ date('F d, Y') }}</p>
    <div class="prose prose-invert max-w-none space-y-6 text-gray-300">
        <section>
            <h2 class="text-2xl font-bold text-white mb-4">1. Information We Collect</h2>
            <p>We collect information that you provide directly to us, including:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Account information (name, email address, password)</li>
                <li>Payment information (processed securely through our payment providers)</li>
                <li>Files and data you choose to backup</li>
                <li>Communication preferences and support inquiries</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Provide, maintain, and improve our services</li>
                <li>Process transactions and send related information</li>
                <li>Send technical notices and support messages</li>
                <li>Respond to your comments and questions</li>
                <li>Monitor and analyze trends and usage</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">3. Data Security</h2>
            <p>We take data security seriously and implement industry-standard measures including:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>End-to-end encryption for all backed up files</li>
                <li>Secure data centers with 24/7 monitoring</li>
                <li>Regular security audits and penetration testing</li>
                <li>Strict access controls and authentication</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">4. Data Sharing</h2>
            <p>We do not sell your personal information. We may share your information only in the following circumstances:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>With your consent</li>
                <li>With service providers who assist in our operations</li>
                <li>To comply with legal obligations</li>
                <li>To protect our rights and safety</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">5. Your Rights</h2>
            <p>You have the right to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Access and receive a copy of your personal data</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Object to or restrict processing</li>
                <li>Data portability</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-white mb-4">6. Contact Us</h2>
            <p>If you have questions about this Privacy Policy, please contact us at:</p>
            <p class="text-purple-400">privacy@example.com</p>
        </section>
    </div>
</div>
</div>
@endsection