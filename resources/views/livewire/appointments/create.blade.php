<div>
    <x-button
        label="Adicionar novo"
        @click="$wire.createAppointmentModal = true"
        responsive
        icon="o-plus"
        class="text-base btn-primary" />

    <x-modal wire:model="createAppointmentModal" title="Novo agendamento">

        <hr class="mb-5">

        <x-form wire:submit="save" >

            <div class="flex flex-col gap-3 p-1 overflow-y-auto">
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-3 md:col-span-2">

                        <x-select
                            label="Tipo de Atendimento"
                            :options="$types_of_treatment"
                            wire:model="treatment_type_id"
                            placeholder="Selecione uma opção" />

                    </div>
                    <div class="col-span-3 md:col-span-1">

                        <x-select
                            label="Modo"
                            :options="$modes"
                            placeholder="Selecione o modo"
                            wire:model="treatment_mode" />

                    </div>
                </div>

                <x-choices label="Assistido" wire:model="patient_id" :options="$patients" search-function="searchPatients"
                    debounce="250ms" min-chars="2" no-result-text="Nenhum resultado..." icon="o-user" single searchable>
                    @scope('item', $patient)
                        <x-list-item :item="$patient" sub-value="address" >
                            {{-- Address --}}
                            <x-slot:sub-value>
                                @if(isset($patient->address->address))
                                {{ $patient->address->address }},
                                @endif
                                @if(isset($patient->address->number))
                                {{ $patient->address->number }} -
                                @endif
                                @if(isset($patient->address->neighborhood))
                                {{ $patient->address->neighborhood }} -
                                @endif
                                @if (isset($patient->address->city))
                                {{ $patient->address->city }} -
                                @endif
                                @if (isset($patient->address->state))
                                {{ $patient->address->state }}
                                @endif
                            </x-slot:sub-value>
                            {{-- Age --}}
                            <x-slot:actions>
                                @php
                                    $ageDifference = now()->parse($patient->birth)->diff(now());
                                    $age = '';
                                    $text = '';
                                    if ($ageDifference->y == 0) {
                                        $age = $ageDifference->m;
                                        $text = $ageDifference->m == 1 ? ' mês' : ' meses';
                                    } else {
                                        $age = $ageDifference->y;
                                        $text = $ageDifference->y == 1 ? ' ano' : ' anos';
                                    }
                                @endphp
                                <x-badge :value="$age . $text" class="bg-indigo-50"/>
                            </x-slot:actions>
                        </x-list-item>
                    @endscope
                </x-choices>

                <div class="px-5 pt-3 pb-5 mt-2 bg-gray-100 rounded-lg">
                    @php
                        $configDate = ['altFormat' => 'd/m/Y', 'dateFormat' => 'Y-m-d', 'minDate' => now()->format('Y-m-d')];
                    @endphp

                    <x-datepicker
                        label="Data"
                        wire:model="date"
                        icon="o-calendar"
                        placehoulder="Selecione uma data"
                        :config="$configDate"
                        class="bg-gray-50" />
                </div>

                <x-textarea
                    label="Observações"
                    wire:model="notes"
                    placeholder="Informações sobre atendimento..."
                    rows="3" />
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.createAppointmentModal = false" class="text-base" />
                <x-button label="Adicionar" type="submit" spinner="save" class="text-base btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
