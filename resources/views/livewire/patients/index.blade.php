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

        <x-table
            :headers="$headers"
            :rows="$patients"
            :sort-by="$sortBy"
            link="patients/{id}/edit"
            with-pagination
            per-page="perPage"
            :per-page-values="[10, 20, 25, 50, 100]"
            class="text-base">

            <x-slot:empty>
                <x-icon name="o-x-circle" label="Nenhum assistido encontrado." />
            </x-slot:empty>

            @scope('cell_name', $patient)
               <div>
                    {{ $patient->name }}
                    <div class="text-sm text-gray-500">
                        @if (!empty($patient->email))
                           {{ $patient->email }} <br/>
                        @endif
                        @if (!empty($patient->phone))
                            {{ $patient->phone }}
                        @endif
                    </div>
               </div>
            @endscope

            @scope('cell_address', $patient)
                <div>
                    @if(!empty($patient->address->address))
                        {{ $patient->address->address }},
                    @endif
                    @if(!empty($patient->address->number))
                        {{ $patient->address->number }} -
                    @endif
                    @if(!empty($patient->address->neighborhood))
                        {{ $patient->address->neighborhood, 15 }}
                    @endif
                </div>
                <div class="text-gray-500">
                    @if (!empty($patient->address->city))
                        {{ $patient->address->city }} -
                    @endif
                    @if (!empty($patient->address->state))
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
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button icon="o-ellipsis-horizontal" class="px-2 btn-sm" />
                    </x-slot:trigger>

                    <x-menu-item title="Ver atendimentos" icon="o-document-text" />
                    <x-menu-item title="Editar" icon="o-pencil-square" link="{{ route('patients.edit', $patient) }}" />
                    <x-menu-item title="Excluir" icon="o-trash" @click="$wire.deleteModalConfirmation = true" wire:click="setPatientIdToDelete({{ $patient['id'] }})" />
                </x-dropdown>
            @endscope
        </x-table>
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
                class="text-base btn-error" />
        </x-slot:actions>
    </x-modal>

</div>
