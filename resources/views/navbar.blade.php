<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>rakitpc.greg</title>
    <style>
        .bottom-nav {
            left: 50%;
            transform: translateX(-50%);
            padding: 0 1rem;
        }

        .nav-button {
            flex: 1;
            min-width: 0;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        /* Hover & Active Effects */
        .nav-button .fa-icon, .nav-button .button-text {
            transition: color 0.3s ease;
        }

        .nav-button:hover .fa-icon,
        .nav-button:hover .button-text,
        .touch-hover .fa-icon,
        .touch-hover .button-text {
            color: #2563eb; /* blue-600 */
        }

        .dark .nav-button:hover .fa-icon,
        .dark .nav-button:hover .button-text,
        .dark .touch-hover .fa-icon,
        .dark .touch-hover .button-text {
            color: #3b82f6; /* blue-500 */
        }

        .nav-button:active .fa-icon,
        .nav-button:active .button-text {
            color: #1d4ed8; /* blue-700 */
        }

        .dark .nav-button:active .fa-icon,
        .dark .nav-button:active .button-text {
            color: #2563eb; /* blue-600 */
        }
    </style>
</head>
<body>
    <div class="bg-blue-100">
        @yield('section')
    </div>

    <div class="fixed bottom-4 z-50 w-auto max-w-md mx-auto bg-white border border-gray-200 rounded-full dark:bg-gray-700 dark:border-gray-600 bottom-nav">
        <div class="flex items-center justify-between h-16 px-4">
            <!-- Home Button -->
            <a href="{{ route('home') }}" 
                class="flex flex-col items-center justify-center rounded-full focus:outline-none nav-button group"
                x-data="{ isActive: false }"
                @touchstart="isActive = true" 
                @touchend="setTimeout(() => isActive = false, 200)"
                :class="{ 'touch-hover': isActive }">
                <i class="fa-icon fa-solid fa-home text-gray-500 dark:text-gray-400 text-xl mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500"></i>
                <span class="button-text text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Home</span>
            </a>
         
            <!-- Build Button -->
            <a href="{{ route('showAllBuilds') }}" 
                class="flex flex-col items-center justify-center rounded-full focus:outline-none nav-button group"
                x-data="{ isActive: false }"
                @touchstart="isActive = true" 
                @touchend="setTimeout(() => isActive = false, 200)"
                :class="{ 'touch-hover': isActive }">
                <i class="fa-icon fa-solid fa-tools text-gray-500 dark:text-gray-400 text-xl mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500"></i>
                <span class="button-text text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Build</span>
            </a>

            <!-- About Button -->
            <a href="{{ route('about') }}" 
                class="flex flex-col items-center justify-center rounded-full focus:outline-none nav-button group"
                x-data="{ isActive: false }"
                @touchstart="isActive = true" 
                @touchend="setTimeout(() => isActive = false, 200)"
                :class="{ 'touch-hover': isActive }">
                <i class="fa-icon fa-solid fa-info-circle text-gray-500 dark:text-gray-400 text-xl mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-500"></i>
                <span class="button-text text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">About</span>
            </a>
        </div>
    </div>
</body>
</html>