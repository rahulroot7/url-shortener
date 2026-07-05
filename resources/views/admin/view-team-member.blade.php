<x-app-layout>

    <div class="max-w-screen-2xl mx-auto px-6 py-6">

        <h1 class="text-xl font-semibold text-gray-800 mb-5">
            Generated Short URLs
        </h1>

        <div class="bg-white rounded-lg shadow">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b">

                <h2 class="text-xl font-semibold text-blue-700">
                    Generated Short URLs
                </h2>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center h-9 px-5 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                    Back
                </a>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100">
                        <tr class="text-sm text-gray-700">

                            <th class="px-5 py-2 text-left font-semibold">
                                Name
                            </th>

                            <th class="px-5 py-2 text-left font-semibold">
                                Email
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Role
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Total Generate URl
                            </th>

                            <th class="px-5 py-2 text-center font-semibold">
                                Total url Hits
                            </th>

                        </tr>
                    </thead>

                    <tbody class="text-sm">

                        @forelse($members as $member)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-5 py-3">
                                        {{ $member->name }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                        {{ $member->email }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $member->role->name ?? '-' }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $member->short_urls_count }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    {{ $member->short_urls_sum_hits ?? 0 }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">
                                    No Short URLs Found.
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
                    {{ $members->firstItem() ?? 0 }}
                    -
                    {{ $members->lastItem() ?? 0 }}
                    of
                    {{ $members->total() }}
                    results
                </p>

                {{ $members->links() }}

            </div>

        </div>

    </div>

</x-app-layout>