<x-app-layout>

    <div class="max-w-screen-2xl mx-auto px-6 py-6">
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
        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800 mb-5">
            Super Admin Dashboard
        </h1>

        <!-- ===================== Clients ===================== -->

        <div x-data="{ openInviteModal: false }">
        <div class="bg-white border border-gray-300 rounded shadow-sm mb-6">
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-3 border-b">

                <h2 class="text-xl font-semibold text-blue-700">
                    Clients
                </h2>

                <button
                    type="button"
                    @click="openInviteModal = true"
                    class="px-5 py-1.5 text-sm font-medium text-white bg-blue-600 border border-blue-700 rounded hover:bg-blue-700 transition">
                    Invite
                </button>

            </div>

            <!-- Table -->

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr class="text-sm text-gray-700">

                            <th class="px-5 py-2 text-left font-semibold">
                                Client Name
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Users
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Total Generated URLs
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Total URL Hits
                            </th>

                        </tr>

                    </thead>

                   <tbody class="text-sm">

                        @forelse ($companies as $company)

                            @php
                                $admin = $company->users->first();
                            @endphp

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    <div class="font-semibold text-gray-800">
                                        {{ $company->name }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $admin?->email ?? 'No Admin Assigned' }}
                                    </div>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $company->users_count }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $company->short_urls_count }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $company->short_urls_sum_hits ?? 0 }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-gray-500">
                                    No companies found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
            <div
                x-show="openInviteModal"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

                <div
                    @click.outside="openInviteModal = false"
                    class="bg-white rounded-lg shadow-xl w-full max-w-2xl">

                    <form action="{{ route('superadmin.invitations.store') }}" method="POST">

                        @csrf

                        <!-- Header -->
                        <div class="flex items-center justify-between border-b px-6 py-4">

                            <h2 class="text-xl font-semibold text-blue-700">
                                Invite New Client
                            </h2>

                            <button
                                type="button"
                                @click="openInviteModal = false"
                                class="text-2xl text-gray-500 hover:text-red-600">
                                &times;
                            </button>

                        </div>

                        <!-- Body -->
                        <div class="p-6">

                            <div class="grid grid-cols-2 gap-5">
                                <input
                                type="hidden"
                                name="role_id"
                                value="{{ $adminRoleId }}">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Name
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        placeholder="Client Name..."
                                        class="w-full border rounded-md px-4 py-2"
                                        required>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        placeholder="example@email.com"
                                        class="w-full border rounded-md px-4 py-2"
                                        required>
                                </div>

                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end gap-3 border-t px-6 py-4">

                            <button
                                type="button"
                                @click="openInviteModal = false"
                                class="px-5 py-2 border rounded-md hover:bg-gray-100">
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Send Invitation
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            </div>

            <!-- Footer -->

            <div class="flex items-center justify-between px-5 py-3 border-t">

                <p class="text-xs text-gray-500">
                    Showing 1 of total 5
                </p>

                <a href="{{ route('superadmin.viewClient.view') }}"
                class="inline-block px-4 py-1.5 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
                    View All
                </a>

            </div>

        </div>

        <!-- ===================== Generated URLs ===================== -->

        <div class="bg-white border border-gray-300 rounded shadow-sm">

            <!-- Header -->

            <div class="flex items-center justify-between px-5 py-3 border-b">

                <h2 class="text-xl font-semibold text-blue-700">
                    Generated Short URLs
                </h2>

                <div class="flex items-center gap-2">

                    <form action="{{ route('superadmin.short-urls.filter') }}" method="GET" class="flex items-center gap-2">

                        <select
                            name="filter"
                            onchange="this.form.submit()"
                            class="h-9 px-3 text-sm border border-gray-300 rounded focus:ring-0 focus:border-blue-500">

                            <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>
                                This Month
                            </option>

                            <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>
                                Last Month
                            </option>

                            <option value="last_week" {{ request('filter') == 'last_week' ? 'selected' : '' }}>
                                Last Week
                            </option>

                            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>
                                Today
                            </option>

                        </select>

                    </form>
                    <a href="{{ route('super-admin.short-urls.download', ['filter' => request('filter', 'this_month')]) }}"
                        class="inline-flex items-center justify-center h-9 px-5 text-sm font-medium text-white bg-blue-600 border border-blue-700 rounded hover:bg-blue-700">
                        Download
                    </a>

                </div>

            </div>

            <!-- Table -->

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr class="text-sm text-gray-700">

                            <th class="px-5 py-2 text-left font-semibold">
                                Short URL
                            </th>

                            <th class="px-5 py-2 text-left font-semibold">
                                Long URL
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Hits
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Company
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Created On
                            </th>

                        </tr>

                    </thead>

                    <tbody class="text-sm">

                        @forelse($shortUrls as $url)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-5 py-3">
                                    <a href="{{ url($url->short_code) }}"
                                        target="_blank"
                                        class="text-blue-700 hover:underline">

                                        {{ url($url->short_code) }}

                                    </a>
                                </td>

                                <td class="px-5 py-3">
                                    <a href="{{ $url->original_url }}"
                                        target="_blank"
                                        class="text-blue-700 hover:underline">

                                        {{ \Illuminate\Support\Str::limit($url->original_url, 50) }}

                                    </a>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $url->hits }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $url->company->name ?? '-' }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $url->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-gray-500">
                                    No Short URLs Found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Footer -->

            <div class="flex items-center justify-between px-5 py-3 border-t">

                <p class="text-xs text-gray-500">
                    Showing 1 of total 5
                </p>

               <a href="{{ route('superadmin.short-url.view') }}"
                class="inline-block px-4 py-1.5 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
                    View All
                </a>

            </div>

        </div>

    </div>

</x-app-layout>