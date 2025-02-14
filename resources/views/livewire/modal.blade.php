<div>
    @if($isOpened)
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="fixed inset-0 bg-gray-800 opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl z-10 relative">
            @if($title)
            <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
            @endif

            @if($childComponent)
            @livewire($childComponent, ['userData' => $componentData])
            @endif
        </div>
    </div>
    @endif
</div>