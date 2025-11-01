<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Complete your checkout</h2>

    <div class="rounded border overflow-hidden">
        <iframe src="{{ $checkout_url }}" style="width:100%; height:720px; border:0;"
            allow="payment *; clipboard-write *; accelerometer; autoplay; gyroscope;">
        </iframe>
    </div>
</x-app-layout>
