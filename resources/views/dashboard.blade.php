<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <!-- Display Error if Exists -->
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-500 text-white rounded">
        {{ session('error') }}
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                    <!-- Filters Section -->
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex flex-wrap gap-2">
                        <input type="number" name="year" placeholder="Year (YYYY)" value="{{ request('year') }}"
                            class="border rounded py-2 px-3">
                        <input type="number" name="month" placeholder="Month (1-12)" value="{{ request('month') }}"
                            class="border rounded py-2 px-3">
                        <input type="number" name="day" placeholder="Day (1-31)" value="{{ request('day') }}"
                            class="border rounded py-2 px-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Apply Filters
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Reset
                        </a>
                    </form>

                    @if (Auth::user()->role === 'super_admin')
                    <!-- Super Admin Section: View all URLs and Clients -->
                    <div>
                        <h4 class="font-bold text-lg mb-2">Clients and Invitations</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Client Name</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Role</th>
                                    <th class="border p-2">Invitation Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td class="border p-2">{{ $client->name }}</td>
                                    <td class="border p-2">{{ $client->email }}</td>
                                    <td class="border p-2">{{ $client->role }}</td>
                                    <td class="border p-2">{{ $client->invitation_status ?? 'No Invitation' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('urls.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Download CSV
                        </a>
                        <a href="{{ route('invitations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Send Invite to New Client
                        </a>
                    </div>

                    <!-- All Shortened URLs -->
                    <div class="mt-4">
                        <h4 class="font-bold text-lg mb-2">All Shortened URLs</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Short URL</th>
                                    <th class="border p-2">Long URL</th>
                                    <th class="border p-2">Created By</th>
                                    <th class="border p-2">Hits</th>
                                    <th class="border p-2">Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($urls as $url)
                                <tr>
                                    <td class="border p-2">
                                        <a href="{{ url('/s/' . $url->short_code) }}" target="_blank" class="text-blue-500 underline">
                                            {{ url('/s/' . $url->short_code) }}
                                        </a>
                                    </td>
                                    <td class="border p-2">{{ $url->long_url }}</td>
                                    <td class="border p-2">{{ $url->user->name }}</td>
                                    <td class="border p-2">{{ $url->hits }}</td>
                                    <td class="border p-2">{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @elseif (Auth::user()->role === 'admin')
                    <!-- Admin Section -->
                    <div class="flex gap-2 mb-4">
                        <a href="{{ route('invitations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Send Invite to New Client
                        </a>
                        <a href="{{ route('urls.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Download CSV
                        </a>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Clients and Invitations</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Client Name</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Role</th>
                                    <th class="border p-2">Invitation Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td class="border p-2">{{ $client->name }}</td>
                                    <td class="border p-2">{{ $client->email }}</td>
                                    <td class="border p-2">{{ $client->role }}</td>
                                    <td class="border p-2">{{ $client->invitation_status ?? 'No Invitation' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <form method="POST" action="{{ route('shorturl.store') }}" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="long_url">
                                Enter a URL to shorten:
                            </label>
                            <input type="url" name="long_url" id="long_url" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Generate Short URL
                        </button>
                    </form>
                    <!-- All Shortened URLs -->
                    <div class="mt-4">
                        <h4 class="font-bold text-lg mb-2">All Shortened URLs</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Short URL</th>
                                    <th class="border p-2">Long URL</th>
                                    <th class="border p-2">Created By</th>
                                    <th class="border p-2">Hits</th>
                                    <th class="border p-2">Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($urls as $url)
                                <tr>
                                    <td class="border p-2">
                                        <a href="{{ url('/s/' . $url->short_code) }}" target="_blank" class="text-blue-500 underline">
                                            {{ url('/s/' . $url->short_code) }}
                                        </a>
                                    </td>
                                    <td class="border p-2">{{ $url->long_url }}</td>
                                    <td class="border p-2">{{ $url->user->name }}</td>
                                    <td class="border p-2">{{ $url->hits }}</td>
                                    <td class="border p-2">{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @elseif (Auth::user()->role === 'member')
                    <!-- Member Section -->
                    <form method="POST" action="{{ route('shorturl.store') }}" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="long_url">
                                Enter a URL to shorten:
                            </label>
                            <input type="url" name="long_url" id="long_url" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Generate Short URL
                        </button>
                    </form>
                    <!-- All Shortened URLs -->
                    <a href="{{ route('urls.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download CSV
                    </a>
                    <div class="mt-4">
                        <h4 class="font-bold text-lg mb-2">All Shortened URLs</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">Short URL</th>
                                    <th class="border p-2">Long URL</th>
                                    <th class="border p-2">Created By</th>
                                    <th class="border p-2">Hits</th>
                                    <th class="border p-2">Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($urls as $url)
                                <tr>
                                    <td class="border p-2">
                                        <a href="{{ url('/s/' . $url->short_code) }}" target="_blank" class="text-blue-500 underline">
                                            {{ url('/s/' . $url->short_code) }}
                                        </a>
                                    </td>
                                    <td class="border p-2">{{ $url->long_url }}</td>
                                    <td class="border p-2">{{ $url->user->name }}</td>
                                    <td class="border p-2">{{ $url->hits }}</td>
                                    <td class="border p-2">{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>You do not have permission to view this page.</p>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $urls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>