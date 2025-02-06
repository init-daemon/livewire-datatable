<div class="p-6 bg-gray-50 rounded-xl">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="w-full md:w-1/3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
            </div>
        </div>
        <div class="w-full md:w-auto flex items-center gap-4">
            <select class="pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <option value="10">10 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
            </select>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Colonnes
                    <span class="bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs font-medium">
                        {{ 2 }}
                    </span>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"

                    class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-10">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gérer les colonnes</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @foreach($this->getColumns() as $key => $label)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer group">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                        <div class="flex flex-col gap-2">
                            <span class="text-xs text-gray-500">{{ 2 }} colonnes
                                sélectionnées</span>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                                <button 
                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    Tout sélectionner
                                </button>
                                <button
                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    Tout désélectionner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    @foreach($this->getColumns() as $field => $label)
                    <livewire:datatable.table-head :key="$field" :label="$label" />
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if(count($this->rows))
                @foreach($this->rows as $row)
                <livewire:datatable.table-row :key="$row['name']" :row="$row"
                    :columns="array_keys(array_filter($selectedColumns))" />
                @endforeach
                @else
                <tr>
                    <td colspan="{{ count(array_filter($selectedColumns)) }}" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-gray-500 text-lg font-medium">Aucun résultat trouvé</span>
                            @if($search)
                            <p class="text-gray-400 text-sm mt-1">
                                Essayez de modifier vos critères de recherche
                            </p>
                            @else
                            <p class="text-gray-400 text-sm mt-1">
                                Aucune donnée disponible dans le tableau
                            </p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if(count($this->rows))
    <div class="mt-6 bg-white px-4 py-3 border border-gray-200 rounded-lg shadow-sm">
        {{ $this->rows->links() }}
    </div>
    @endif
</div>