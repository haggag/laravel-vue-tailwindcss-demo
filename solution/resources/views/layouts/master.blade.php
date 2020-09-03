<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance App</title>

    <!-- Fonts -->

    <!-- Scripts -->

    <script>
        window.Laravel = @json([
           'csrfToken' => csrf_token(),
           'apiToken' => current_user()->api_token ?? null
       ]);
    </script>

    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- Styles -->
    <style>[v-cloak] { display: none; }</style>
    <link rel="stylesheet" href="/css/main.css">

</head>
<body class="bg-gray-100 h-screen  antialiased ">
<div id="app">

    <nav class="bg-white">
        <div class="max-w-7xl  mx-auto ">
            <div class="flex items-center justify-between h-16">

                <x-logo />
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <div><notifications-badge url="{{ route('notifications') }}" :init_count={{current_user()->unreadNotifications()->count()}} /></div>

                        <div>
                        <profile-dropdown avatar="{{ Auth::user()->avatar() }}" logout_url="{{ route('logout') }}" />
                        </div>
                        <!-- Profile dropdown -->

                        <span class="ml-3 text-sm text-gray-500">{{Auth::user()->name}}</span>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                        <!-- Menu open: "hidden", Menu closed: "block" -->
                        <svg class="block h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <!-- Menu open: "block", Menu closed: "hidden" -->
                        <svg class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!--
          Mobile menu, toggle classes based on menu state.

          Open: "block", closed: "hidden"
        -->
        <div class="block md:hidden">

            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full"
                             src="{{ Auth::user()->avatar() }}"
                             alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                        <div class="mt-1 text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2">


                    <a :href="this.logout_url" onclick="event.preventDefault();document.getElementById('logout-form-mobile').submit();"
                       class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Sign
                        out</a>



                    <form ref="form" id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-blue-900 shadow">


        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-56 flex flex-wrap items-center justify-between ">
            @section('header')
            @show
        </div>
    </header>
    <main class="bg-gray-100">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-56">

            @yield('content')

        </div>
    </main>
    @section('footer')
    @show
</div>

<script>
    const initialData = {
        "user": @json(current_user()),
        "csrf": "{{csrf_token()}}"
    }

    vueApp.init(initialData)
    vueApp.$mount('#app')



</script>
</body>
</html>


