@props(['title', 'subtitle' => null])

<div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-200">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if(isset($actions))
        <div class="flex items-center space-x-2">
            {{ $actions }}
        </div>
    @endif
</div>