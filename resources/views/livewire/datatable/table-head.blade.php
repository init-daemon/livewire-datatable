<th wire:click="$parent.sort('{{ $field }}')"
    class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
    <div class="flex items-center gap-2">
        <span>{{ $label }}</span>
        <div class="flex flex-col h-4 -space-y-1">
            <svg class="w-3 h-3 {{ $field === $sortField && $sortDirection === 'asc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
            <svg class="w-3 h-3 {{ $field === $sortField && $sortDirection === 'desc' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>
</th>