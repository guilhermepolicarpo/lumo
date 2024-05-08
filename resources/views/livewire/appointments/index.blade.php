<div>

    {{-- HEADER --}}
    <x-header title="Agendamentos" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" icon="o-funnel" responsive @click="$wire.drawer = true" class="text-base btn-outline" />
            <x-button label="Adicionar novo" link="#" responsive icon="o-plus"
                class="text-base btn-primary" />
        </x-slot:actions>
    </x-header>


    {{-- TABLE --}}
    <x-card>
        @if ($appointments->count() == 0)
            <p>Nenhum agendamento encontrado.</p>
        @else
        <x-table :headers="$headers" :rows="$appointments" :sort-by="$sortBy" link="#" with-pagination class="text-base">
            @scope('cell_patient_name', $appointment)
                {{ Str::words($appointment->patient->name, 4, '...') }}
            @endscope
            @scope('cell_date', $appointment)
                {{ now()->parse($appointment->date)->format('d/m/Y') }}
            @endscope
            @scope('actions', $appointment)
            <div class="flex">
                <x-button icon="o-pencil-square" link="#" spinner
                    tooltip-left="Editar" class="px-2 text-indigo-500 btn-ghost btn-sm" />

                <x-button icon="o-trash" @click="$wire.deleteModalConfirmation = true"
                    wire:click="setIdToDelete({{ $appointment['id'] }})" spinner tooltip-left="Excluir"
                    class="px-2 text-red-500 btn-ghost btn-sm" />
            </div>
            @endscope
        </x-table>
        @endif
    </x-card>


    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filtros" subtitle="Filtrar agendamentos" separator with-close-button right
        class="w-11/12 lg:w-1/3">

        @php
            $configDate = ['altFormat' => 'd/m/Y'];
        @endphp

        <x-datepicker label="Data" wire:model="date" icon="o-calendar" placehoulder="Selecione uma data" :config="$configDate" />

        <x-slot:actions>
            <x-button label="Limpar" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Filtrar" class="btn-primary" icon="o-check" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este agendamento?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteModalConfirmation = false" />

            <x-button label="Excluir" wire:click="delete({{ $idToDelete }})"
                class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
