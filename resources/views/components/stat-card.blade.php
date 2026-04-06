<div class="bg-white rounded-lg shadow p-6 dark:bg-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium dark:text-gray-400">
                {{ $label }}
            </p>
            <p class="text-3xl font-bold text-gray-900 mt-2 dark:text-white">
                {{ $value }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center h-12 w-12 rounded-md {{ $bgColor ?? 'bg-blue-100' }}">
                {!! $icon ?? '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>' !!}
            </div>
        </div>
    </div>
    
    <!-- Optional footer text or trend indicator -->
    @if (isset($footer))
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <p class="text-xs text-gray-600 dark:text-gray-400">
                {!! $footer !!}
            </p>
        </div>
    @endif
</div>
