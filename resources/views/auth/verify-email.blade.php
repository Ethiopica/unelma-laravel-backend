<x-layout>
    <x-slot:title>
        Verify Your Email - {{ config('app.name') }}
    </x-slot:title>

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white shadow rounded-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Verify your email address</h1>
            <p class="text-gray-600 mb-6">Before proceeding, please check your email for a verification link. If you did not receive the email, you can request another below.</p>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                    Resend Verification Email
                </button>
            </form>
        </div>
    </div>
</x-layout>


