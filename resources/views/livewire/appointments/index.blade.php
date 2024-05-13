<div>

    {{-- HEADER --}}
    <x-header title="Agendamentos" size="text-2xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filtros" icon="o-funnel" responsive @click="$wire.drawer = true" class="text-base btn-outline" >
                <x-badge value="7" class="badge-secondary indicator-item" />
            </x-button>

            {{-- Create Appointment --}}
            <livewire:appointments.create :types_of_treatment="$types_of_treatment" :modes="$modes" :key="'create-appointment'" @saved="$refresh" />
        </x-slot:actions>
    </x-header>



    <x-card>
        {{-- APPLIED FILTERS --}}
        @if ($date || $search || $selectedMode || $selectedType || $selectedStatus)
            <div class="flex items-center gap-2 mb-3">
                <p class="text-sm font-medium">Filtros aplicados:</p>
                @if ($date)
                    <x-badge value="Data: {{ now()->parse($date)->format('d/m/Y') }}" class="text-xs font-medium badge-primary" />
                @endif
                @if ($search)
                    <x-badge value="Nome: {{ $search }}" class="text-xs font-medium badge-primary" />
                @endif
                @if ($selectedStatus)
                    <x-badge value="Status: {{ $selectedStatus }}" class="text-xs font-medium badge-primary" />
                @endif
                @if ($selectedType)
                    <x-badge value="Tipo: {{ $types_of_treatment->get($selectedType)->name }}" class="text-xs font-medium badge-primary" />
                @endif
                @if ($selectedMode)
                    <x-badge value="Modo: {{ $selectedMode }}" class="text-xs font-medium badge-primary" />
                @endif
                <x-button label="Limpar" wire:click="clear" no-wire-navigate class="text-xs btn-ghost btn-sm" />
            </div>
        @endif

        @if ($appointments->count() !== 0)

            {{-- TABLE --}}
            <x-table :headers="$headers" :rows="$appointments" :sort-by="$sortBy" link="#" with-pagination class="text-base">
                @scope('cell_patient_name', $appointment)
                    {{ Str::words($appointment->patient->name, 4, '...') }}
                @endscope
                @scope('cell_date', $appointment)
                    {{ now()->parse($appointment->date)->format('d/m/Y') }}
                @endscope
                @scope('cell_status', $appointment)
                    @if ($appointment->status === 'Confirmado')
                        <x-badge value="{{ $appointment->status }}" class="text-xs font-semibold leading-5 text-indigo-800 bg-indigo-100" />
                    @endif
                    @if ($appointment->status === 'Em espera')
                        <x-badge value="{{ $appointment->status }}" class="text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100" />
                    @endif
                    @if ($appointment->status === 'Atendido')
                        <x-badge value="{{ $appointment->status }}" class="text-xs font-semibold leading-5 text-green-800 bg-green-100" />
                    @endif
                    @if ($appointment->status === 'Faltou')
                        <x-badge value="{{ $appointment->status }}" class="text-xs font-semibold leading-5 text-red-800 bg-red-100" />
                    @endif
                @endscope
                @scope('actions', $appointment)
                <div class="flex items-center justify-end ">
                    @switch($appointment->status)
                        @case('Confirmado')
                            <x-button
                                label="Receber"
                                tooltip="Receber assistido"
                                wire:click="$set('appointmentId', {{ $appointment->id }})"
                                @click="$wire.receivePatientModalConfirmation = true"
                                spinner
                                class="px-2 mr-1 text-indigo-500 border-indigo-500 btn-outline btn-sm" />

                            <x-button icon="o-trash" @click="$wire.deleteModalConfirmation = true"
                                wire:click="$set('idToDelete', {{ $appointment->id }})" spinner tooltip-left="Excluir agendamento"
                                class="px-2 text-red-500 btn-ghost btn-sm" />
                            @break

                        @case('Em espera')
                            <x-button label="Atender" tooltip="Atender assistido" spinner class="px-2 mr-1 text-indigo-500 border-indigo-500 btn-outline btn-sm" />
                            <x-button icon="o-trash" class="px-2 text-red-500 opacity-50 btn-ghost btn-sm" />
                            @break

                        @case('Atendido')
                            <x-button
                                label="Ver Atend."
                                tooltip="Ver atendimento"
                                spinner
                                class="px-2 mr-1 text-indigo-500 border-indigo-500 btn-outline btn-sm" />
                            <x-button icon="o-trash" class="px-2 text-red-500 opacity-50 btn-ghost btn-sm" />
                            @break

                        @default
                            <x-button label="Receber" class="px-2 mr-1 text-indigo-500 bg-white border-indigo-500 opacity-50 btn-sm" />
                            <x-button icon="o-trash" class="px-2 text-red-500 opacity-50 btn-ghost btn-sm" />
                    @endswitch


                </div>
                @endscope
            </x-table>
        @else
            <p>Nenhum agendamento encontrado.</p>
        @endif
    </x-card>


    {{-- FILTER DRAWER --}}
    <x-drawer wire:model="drawer" title="Filtros" subtitle="Filtrar agendamentos" separator with-close-button right
        class="w-11/12 lg:w-1/3">

        @php
            $configDate = ['altFormat' => 'd/m/Y', 'dateFormat' => 'Y-m-d'];
        @endphp

        <div class="flex flex-col gap-3">
            <x-datepicker label="Data" wire:model.lazy="date" icon="o-calendar" placehoulder="Selecione uma data" :config="$configDate" />

            <x-select label="Status" wire:model.lazy="selectedStatus" :options="$status" placeholder="Todos" class="text-base" icon="o-check-circle" />

            <x-select label="Tipo de Atendimento" wire:model.lazy="selectedType" :options="$types_of_treatment" placeholder="Todos"
                class="text-base" icon="o-queue-list" />

            <x-select label="Modo de Atendimento" wire:model.lazy="selectedMode" :options="$modes" placeholder="Todos" class="text-base" icon="o-map" />
        </div>

        <x-slot:actions>
            <x-button label="Limpar" icon="o-x-mark" wire:click="clear" spinner class="text-base" />
            <x-button label="Filtrar" class="text-base btn-primary" icon="o-check" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>


    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModalConfirmation" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este agendamento?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteModalConfirmation = false" />
            <x-button label="Excluir" wire:click="delete({{ $idToDelete }})"
                class="text-white bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>


    {{-- RECEIVE PATIENT CONFIRMATION MODAL --}}
    <x-modal wire:model="receivePatientModalConfirmation" title="Tem certeza?">
        <p>Confirma que o assistido chegou para o atendimento?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.receivePatientModalConfirmation = false" />
            <x-button label="Confirmar" wire:click="receivePatient({{ $appointmentId }})"
                class="btn-primary" />
        </x-slot:actions>
    </x-modal>

</div>
