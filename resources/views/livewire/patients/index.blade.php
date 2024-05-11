<div>

    {{-- HEADER --}}
    <x-header title="Assistidos" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input
                placeholder="Pesquisar..."
                wire:model.live.debounce.250ms="search"
                clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button
                label="Adicionar assistido"
                link="{{ route('patients.create') }}"
                responsive
                icon="o-plus"
                class="text-base btn-primary" />
        </x-slot:actions>
    </x-header>


    {{-- TABLE --}}
    <x-card>
        @if ($patients->count() == 0)
            <p>Nenhum assistido encontrado.</p>
        @else
        <x-table :headers="$headers" :rows="$patients" :sort-by="$sortBy" link="patients/{id}/edit" with-pagination class="text-base">
            @scope('cell_address', $patient)
                <div>
                    @if(isset($patient->address->address))
                        {{ $patient->address->address }},
                    @endif
                    @if(isset($patient->address->number))
                        {{ $patient->address->number }} -
                    @endif
                    @if(isset($patient->address->neighborhood))
                        {{ Str::limit($patient->address->neighborhood, 15) }}
                    @endif
                </div>
                <div class="text-gray-500">
                    @if (isset($patient->address->city))
                        {{ $patient->address->city }} -
                    @endif
                    @if (isset($patient->address->state))
                        {{ $patient->address->state }}
                    @endif
                </div>
            @endscope
            @scope('cell_birth', $patient)
                @php
                    $ageDifference = now()->parse($patient->birth)->diff(now());
                @endphp

                <div>
                    @if ($ageDifference->y == 0)
                        {{ $ageDifference->m }} {{ $ageDifference->m == 1 ? 'mês' : 'meses' }}
                    @else
                        {{ $ageDifference->y }} {{ $ageDifference->y == 1 ? 'ano' : 'anos' }}
                    @endif
                </div>
                <div class="text-gray-500">
                    {{ now()->parse($patient->birth)->format('d/m/Y') }}
                </div>
            @endscope
            @scope('actions', $patient)
                <div class="flex">
                    <x-button
                        icon="o-document-text"
                        link="#"
                        spinner
                        tooltip-left="Ver atendimentos"
                        class="px-2 text-indigo-500 btn-ghost btn-sm" />

                    <x-button
                        icon="o-pencil-square"
                        link="{{ route('patients.edit', $patient) }}"
                        spinner
                        tooltip-left="Editar"
                        class="px-2 text-indigo-500 btn-ghost btn-sm" />

                    <x-button
                        icon="o-trash"
                        @click="$wire.deleteModalConfirmation = true"
                        wire:click="setPatientIdToDelete({{ $patient['id'] }})"
                        spinner
                        tooltip-left="Excluir"
                        class="px-2 text-red-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
        @endif
    </x-card>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este assistido?</p>

        <x-slot:actions>
            <x-button
                label="Cancelar"
                @click="$wire.deleteModalConfirmation = false" />

            <x-button
                label="Excluir"
                wire:click="delete({{ $patientIdToDelete }})"
                class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
