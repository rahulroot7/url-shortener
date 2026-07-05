<x-app-layout>

    <div class="max-w-screen-2xl mx-auto px-6 py-6">

        <h1 class="text-xl font-semibold text-gray-800 mb-5">
            Generated Short URLs
        </h1>

        <div class="bg-white rounded-lg shadow">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b">

            <!-- Left -->
            <div class="flex items-center gap-3">

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center h-9 px-4 text-sm font-medium text-white bg-gray-600 rounded hover:bg-gray-700">
                    Back
                </a>

                <h2 class="text-xl font-semibold text-blue-700">
                    Generated Short URLs
                </h2>

            </div>

            <!-- Right -->
            <div class="flex items-center gap-3">

                <form action="{{ route('superadmin.short-urls.viewFiltter') }}" method="GET">

                    <select
                        name="filter"
                        onchange="this.form.submit()"
                        class="h-9 px-3 text-sm border border-gray-300 rounded focus:ring-0 focus:border-blue-500">

                        <option value="this_month"
                            {{ request('filter', 'this_month') == 'this_month' ? 'selected' : '' }}>
                            This Month
                        </option>

                        <option value="last_month"
                            {{ request('filter') == 'last_month' ? 'selected' : '' }}>
                            Last Month
                        </option>

                        <option value="last_week"
                            {{ request('filter') == 'last_week' ? 'selected' : '' }}>
                            Last Week
                        </option>

                        <option value="today"
                            {{ request('filter') == 'today' ? 'selected' : '' }}>
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

                            <th class="px-5 py-3 text-left font-semibold">
                                Short URL
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Long URL
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Hits
                            </th>
                            <th class="px-5 py-3 text-center font-semibold">
                                Company Name
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
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
                                        class="text-blue-600 hover:underline">

                                        {{ url($url->short_code) }}

                                    </a>
                                </td>

                                <td class="px-5 py-3 text-center">

                                    <a href="{{ $url->original_url }}"
                                        target="_blank"
                                        class="text-blue-600 hover:underline">

                                        {{ Str::limit($url->original_url, 60) }}

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
                    {{ $shortUrls->firstItem() ?? 0 }}
                    -
                    {{ $shortUrls->lastItem() ?? 0 }}
                    of
                    {{ $shortUrls->total() }}
                    results
                </p>

                {{ $shortUrls->links() }}

            </div>

        </div>

    </div>

</x-app-layout>