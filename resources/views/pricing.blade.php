<x-app-layout>
    <h2 class="text-xl font-bold mb-6">Choose a plan</h2>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-3 mb-4 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('checkout') }}" class="flex gap-4">
        @csrf

        <button type="submit" name="plan" value="monthly"
            class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
            Monthly Plan
        </button>

        <button type="submit" name="plan" value="annual"
            class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
            Annual Plan
        </button>
    </form>
</x-app-layout>
