<x-app-layout>

    <div class="max-w-screen-2xl mx-auto px-6 py-6">

        <div class="bg-white rounded-lg shadow">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b">

                <h2 class="text-xl font-semibold text-blue-700">
                    All Clients
                </h2>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                    Back
                </a>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr class="text-sm text-gray-700">

                            <th class="px-5 py-3 text-left font-semibold">
                                Client Name
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Users
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Total Generated URLs
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Total URL Hits
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Created On
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

                                <td class="px-5 py-3 text-center">
                                    {{ $company->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                    No Clients Found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-5 py-4 border-t">

                <p class="text-sm text-gray-600">
                    Showing
                    {{ $companies->firstItem() ?? 0 }}
                    -
                    {{ $companies->lastItem() ?? 0 }}
                    of
                    {{ $companies->total() }}
                    clients
                </p>

                {{ $companies->links() }}

            </div>

        </div>

    </div>

</x-app-layout>