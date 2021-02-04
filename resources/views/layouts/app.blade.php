<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-900 text-white">
    <header class="border-b border-gray-800">
        <header class="container mx-auto flex items-center justify-between px-4 py-6">
            <div class="flex items-center">
                <a href="/">
                    <img src="images/laracasts-logo.svg"
                        alt="laracasts"
                        class="w-32 flex-none"
                    >
                </a>
                <ul class="flex ml-16 space-x-8">
                    <li><a href="#" class="hover:text-gray-400">Games</a></li>
                    <li><a href="#" class="hover:text-gray-400">Reviews</a></li>
                    <li><a href="#" class="hover:text-gray-400">Coming Soon</a></li>
                </ul>
            </div>
            <div class="flex items-center">
                <div class="relative">
                    <input type="text"
                        class="bg-gray-800 text-sm rounded-full focus:outline-none focus:ring w-64 px-3 py-1 pl-8"
                        placeholder="Search..."
                    >
                    <div class="absolute top-0 flex items-center h-full ml-2">
                        <svg class="fill-current text-gray-400 w-4"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M16.32 14.9l5.39 5.4a1 1 0 01-1.42 1.4l-5.38-5.38a8 8 0 111.41-1.41zM10 16a6 6 0 100-12 6 6 0 000 12z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-6">
                    <a href="#">
                        <img src="images/avatar.jpg"
                            alt="avatar"
                            class="rounded-full w-8"
                        >
                    </a>
                </div>
            </div>
        </header>

        <main class="py-8">
            @yield('content')
        </main>

        <footer class="border-t border-gray-800">
            <div class="container mx-auto px-4 py-6">
                Powered by
                <a href="https://www.igdb.com/api"
                    target="_blank"
                    class="underline hover:text-gray-400"
                >
                    IGDB API
                </a>
            </div>
        </footer>
    </header>
</body>
</html>
