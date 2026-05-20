<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">{{ $title ?? 'Dashboard' }}</h1>
    <div class="flex items-center space-x-4">
        <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
        </form>
    </div>
</header>
