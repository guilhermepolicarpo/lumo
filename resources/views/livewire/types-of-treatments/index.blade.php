<div>

    {{-- HEADER --}}
    <x-header title="Tipos de Atendimento" subtitle="Atendimentos realizados no centro espírita" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Adicionar novo" link="{{ route('types-of-treatments.create') }}" responsive icon="o-plus"
                class="text-base btn-primary" />
        </x-slot:actions>
    </x-header>


    {{-- TABLE --}}
    <x-card>
        @if ($typesOfTreatments->count() == 0)
            <p>Nenhuma tipo de atendimento encontrado.</p>
        @else
        <x-table :headers="$headers" :rows="$typesOfTreatments" :sort-by="$sortBy" link="types-of-treatments/{id}/edit"
            with-pagination class="text-base">
            @scope('cell_description', $typeOfTreatment)
            {{ Str::limit($typeOfTreatment['description'], 230) }}
            @endscope
            @scope('actions', $typeOfTreatment)
            <div class="flex">
                <x-button icon="o-pencil-square" link="{{ route('types-of-treatments.edit', $typeOfTreatment) }}" spinner
                    tooltip-left="Editar" class="px-2 text-indigo-500 btn-ghost btn-sm" />

                <x-button icon="o-trash" @click="$wire.deleteModalConfirmation = true"
                    wire:click="setIdToDelete({{ $typeOfTreatment['id'] }})" spinner tooltip-left="Excluir"
                    class="px-2 text-red-500 btn-ghost btn-sm" />
            </div>
            @endscope
        </x-table>
        @endif
    </x-card>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este tipo de atendimento?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteModalConfirmation = false" />

            <x-button label="Excluir" wire:click="delete({{ $idToDelete }})"
                class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
