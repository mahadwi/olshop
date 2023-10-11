<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Olshop</title>        

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css','resources/js/app.js'])
        <!-- Styles -->
        @stack('style')
    </head>
    <body class="bg-gray-50 dark:bg-gray-800">
        @guest
            @yield('content')
        @endguest
        
        @auth
            @include('layouts/navbar')
            <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
                
                <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
                  <main>
                    @yield('content')

                  </main>
                </div>
              
            </div>
        @endauth
        
        @stack('js');
    </body>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</html>
