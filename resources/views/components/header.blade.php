<header class="flex items-center justify-between py-4 px-6 bg-white border-b border-gray-100">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">☰</button>
        <h1 class="text-lg font-semibold">{{ $title ?? config('app.name') }}</h1>
    </div>

    <div class="flex items-center gap-4">
        {{ $slot }}
    </div>
</header>
