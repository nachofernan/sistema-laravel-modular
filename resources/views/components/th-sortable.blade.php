@props(['field', 'label', 'sortBy', 'sortDirection', 'align' => 'left'])

<th scope="col" wire:click="sortByField('{{ $field }}')"
    {{ $attributes->class(["px-6 py-3 text-{$align} text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"]) }}>
    <div class="flex items-center {{ $align === 'center' ? 'justify-center' : '' }} space-x-1">
        <span>{{ $label }}</span>
        @if($sortBy === $field)
            @if($sortDirection === 'asc')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            @endif
        @else
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
        @endif
    </div>
</th>
