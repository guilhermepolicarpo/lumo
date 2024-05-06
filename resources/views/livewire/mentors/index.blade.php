<div>

    {{-- HEADER --}}
    <x-header title="Mentores" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Adicionar mentor" link="{{ route('mentors.create') }}" responsive icon="o-plus"
                class="btn-primary" />
        </x-slot:actions>
    </x-header>


    {{-- TABLE --}}
    <x-card>
        @if ($mentors->count() == 0)
            <p>Nenhum mentor encontrado.</p>
        @else
        <x-table :headers="$headers" :rows="$mentors" :sort-by="$sortBy" link="mentors/{id}/edit" with-pagination>
            @scope('actions', $mentor)
            <div class="flex">
                <x-button icon="o-pencil-square" link="{{ route('mentors.edit', $mentor) }}" spinner
                    tooltip-left="Editar" class="px-2 text-indigo-500 btn-ghost btn-sm" />

                <x-button icon="o-trash" @click="$wire.deleteModalConfirmation = true"
                    wire:click="setMentorIdToDelete({{ $mentor['id'] }})" spinner tooltip-left="Excluir"
                    class="px-2 text-red-500 btn-ghost btn-sm" />
            </div>
            @endscope
        </x-table>
        @endif
    </x-card>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este mentor?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteModalConfirmation = false" />

            <x-button label="Excluir" wire:click="delete({{ $mentorIdToDelete }})"
                class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
