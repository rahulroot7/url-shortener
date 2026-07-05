<x-guest-layout>

    <div class="max-w-lg mx-auto mt-10 bg-white shadow rounded p-6">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif
        <h2 class="text-2xl font-semibold mb-6">
            Accept Invitation
        </h2>

        <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label>Name</label>

                <input
                    type="text"
                    value="{{ $invitation->name }}"
                    class="w-full border rounded mt-1"
                    readonly>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label>Email</label>

                <input
                    type="email"
                    value="{{ $invitation->email }}"
                    class="w-full border rounded mt-1"
                    readonly>
            </div>

            {{-- Show Company Name only for Super Admin invitations --}}
            @if(is_null($invitation->company_id))

                <div class="mb-4">
                    <label>Company Name</label>

                    <input
                        type="text"
                        name="company_name"
                        class="w-full border rounded mt-1"
                        required>
                </div>

            @endif

            <!-- Password -->
            <div class="mb-4">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded mt-1"
                    required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label>Confirm Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded mt-1"
                    required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            <button
                class="w-full bg-blue-600 text-white py-2 rounded">
                Create Account
            </button>

        </form>

    </div>

</x-guest-layout>