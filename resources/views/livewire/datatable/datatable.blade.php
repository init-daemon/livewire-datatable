<div class="p-6 bg-gray-50 rounded-xl">
    <!-- En-tête avec recherche et filtres -->
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
            <select wire:model.live="perPage"
                class="pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
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
                        {{ count(array_filter($selectedColumns)) }}
                    </span>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-10">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gérer les colonnes</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @foreach($this->getColumns() as $key => $label)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer group">
                            <input type="checkbox" wire:model.live="selectedColumns.{{ $key }}"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                        <div class="flex flex-col gap-2">
                            <span class="text-xs text-gray-500">{{ count(array_filter($selectedColumns)) }} colonnes
                                sélectionnées</span>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                                <button wire:click="selectAllColumns"
                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    Tout sélectionner
                                </button>
                                <button wire:click="deselectAllColumns"
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

    <!-- Section des filtres -->
    <div class="mb-6">
        <button wire:click="$toggle('showFilters')"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filtres
            @if(array_filter($filters))
            <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs font-medium">
                {{ count(array_filter($filters)) }}
            </span>
            @endif
        </button>

        @if($showFilters)
        <div class="mt-4 p-6 bg-white rounded-lg border border-gray-200 shadow-sm space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Filtre par statut -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <div class="relative">
                        <select wire:model.live="filters.status"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Tous les statuts</option>
                            @foreach($this->getStatusOptions() as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filtre par date -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date de début</label>
                    <div class="relative">
                        <input type="date" wire:model.live="filters.created_at_from"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Sélectionner une date">
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                    <div class="relative">
                        <input type="date" wire:model.live="filters.created_at_to"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Sélectionner une date">
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filtre par solde -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Solde minimum</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" wire:model.live="filters.balance_min"
                            class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Solde maximum</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" wire:model.live="filters.balance_max"
                            class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button wire:click="resetFilters"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Réinitialiser les filtres
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    @foreach($this->getColumns() as $field => $label)
                    @if($selectedColumns[$field])
                    <livewire:datatable.table-head :key="$field" :label="$label" :field="$field"
                        :sort-field="$sortField" :sort-direction="$sortDirection" />
                    @endif
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

    <!-- Pagination -->
    @if(count($this->rows))
    <div class="mt-6 bg-white px-4 py-3 border border-gray-200 rounded-lg shadow-sm">
        {{ $this->rows->links() }}
    </div>
    @endif
</div>