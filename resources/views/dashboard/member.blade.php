<x-app-layout>

    <div class="max-w-screen-2xl mx-auto px-6 py-6">

        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800 mb-5">
            Member Dashboard
        </h1>

        <div x-data="{ openModal: false }" class="bg-white rounded-lg shadow">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-3 border-b">

                <div class="flex items-center gap-4">

                    <h2 class="text-xl font-semibold text-blue-700">
                        Generate Short URLs
                    </h2>

                    <button
                        type="button"
                        @click="openModal = true"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-700 rounded hover:bg-blue-700 transition">
                        Generate
                    </button>

                </div>
                <!-- Right -->
            <div class="flex items-center gap-3">

                <form action="{{ route('member.short-urls.filter') }}" method="GET">

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
                <a href="{{ route('member.short-urls.download', ['filter' => request('filter', 'this_month')]) }}"
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

                                        {{ \Illuminate\Support\Str::limit($url->original_url, 60) }}

                                    </a>
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $url->hits }}
                                </td>

                                <td class="px-5 py-3 text-center">
                                    {{ $url->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                    No Short URLs Found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-5 py-4 border-t">

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

            <!-- Generate Modal -->
            <div
                x-show="openModal"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

                <div
                    @click.outside="openModal = false"
                    class="bg-white rounded-lg shadow-xl w-full max-w-2xl">

                    <form action="{{ route('member.short-url-member.store') }}" method="POST">

                        @csrf

                        <input
                            type="hidden"
                            name="user_id"
                            value="{{ auth()->id() }}">

                        <input
                            type="hidden"
                            name="company_id"
                            value="{{ auth()->user()->company_id }}">

                        <div class="flex items-center justify-between border-b px-6 py-4">

                            <h2 class="text-xl font-semibold text-blue-700">
                                Generate Short URL
                            </h2>

                            <button
                                type="button"
                                @click="openModal = false"
                                class="text-2xl text-gray-500 hover:text-red-600">

                                &times;

                            </button>

                        </div>

                        <div class="p-6">

                            <label
                                for="original_url"
                                class="block mb-2 text-sm font-medium text-gray-700">

                                Long URL

                            </label>

                            <input
                                id="original_url"
                                type="url"
                                name="original_url"
                                value="{{ old('original_url') }}"
                                placeholder="https://example.com"
                                required
                                class="w-full border border-gray-300 rounded-md px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                            @error('original_url')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div class="flex justify-end gap-3 border-t px-6 py-4">

                            <button
                                type="button"
                                @click="openModal = false"
                                class="px-5 py-2 border rounded-md hover:bg-gray-100">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">

                                Generate

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>