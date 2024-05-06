<div>

    {{-- HEADER --}}
    <x-header title="Orientações" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Adicionar orientação" link="{{ route('orientations.create') }}" responsive icon="o-plus"
                class="text-base btn-primary" />
        </x-slot:actions>
    </x-header>


    {{-- TABLE --}}
    <x-card>
        @if ($orientations->count() == 0)
            <p>Nenhuma orientação encontrada.</p>
        @else
        <x-table :headers="$headers" :rows="$orientations" :sort-by="$sortBy" link="orientations/{id}/edit" with-pagination class="text-base">
            @scope('cell_description', $orientation)
                {{ Str::limit($orientation['description'], 230) }}
            @endscope
            @scope('actions', $orientation)
                <div class="flex">
                    <x-button icon="o-pencil-square" link="{{ route('orientations.edit', $orientation) }}" spinner
                        tooltip-left="Editar" class="px-2 text-indigo-500 btn-ghost btn-sm" />

                    <x-button icon="o-trash" @click="$wire.deleteModalConfirmation = true"
                        wire:click="setIdToDelete({{ $orientation['id'] }})" spinner tooltip-left="Excluir"
                        class="px-2 text-red-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
        @endif
    </x-card>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir esta orientação?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteModalConfirmation = false" />

            <x-button label="Excluir" wire:click="delete({{ $idToDelete }})"
                class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
