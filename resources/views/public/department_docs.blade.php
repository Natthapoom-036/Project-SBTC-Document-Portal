<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documents - {{ $department->name }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">DMS Portal</a>
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-600 font-medium">{{ $department->name }}</span>
                    </div>
                     <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Admin Panel
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Admin Login
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-6 flex items-center justify-between">
                         <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Documents for {{ $department->name }}</h1>
                         <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                             &larr; Back to Departments
                         </a>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            @if($documents->isEmpty())
                                <p class="text-center text-gray-500 py-4">No documents available for this department.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Title</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded At</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($documents as $doc)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <a href="{{ route('documents.view', $doc->filename) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline">
                                                            {{ $doc->title }}
                                                        </a>
                                                        <div class="text-xs text-gray-500">{{ $doc->filename }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $doc->created_at->format('d M Y, H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('documents.download', $doc->filename) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                            Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">© {{ date('Y') }} Document Management System. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
