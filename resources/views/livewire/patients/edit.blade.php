<div>
    {{-- HEADER --}}
    <x-header title="Editar assistido" progress-indicator />

    <x-card>
        <x-form wire:submit="save">

            {{-- Basic section --}}
            <div class="grid-cols-6 lg:grid">
                <div class="col-span-2">
                    <x-header title="Informações pessoais" subtitle="Informações básicas do assistido" size="text-1xl" />
                </div>
                <div class="grid col-span-4 gap-3">
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="md:col-span-2">

                            <x-input label="Nome" wire:model="patientState.name" icon="o-user" placeholder="João Silva" />

                        </div>

                        <x-datetime label="Nascimento" wire:model="patientState.birth" icon="o-calendar" max="{{ now()->format('Y-m-d') }}" />

                    </div>
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="md:col-span-2">

                            <x-input label="E-mail" wire:model="patientState.email" icon="o-envelope" placeholder="email@exemplo.com" />

                        </div>

                        <x-input
                            id="phone"
                            label="Telefone"
                            wire:model="patientState.phone"
                            icon="o-phone"
                            x-mask:dynamic="$input.length < 15 ? '(99) 9999-9999' : '(99) 99999-9999'"
                            placeholder="(00) 00000-0000"
                             />
                    </div>
                </div>
            </div>


            {{-- Details section --}}
            <hr class="my-5" />

            <div class="grid-cols-6 lg:grid">
                <div class="md:col-span-2">
                    <x-header title="Endereço" subtitle="Endereço do assistido" size="text-1xl" />
                </div>
                <div class="grid col-span-4 gap-3">
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="col-span-1">

                            <x-input label="CEP" wire:model="addressState.zip_code" x-on:blur="$wire.getAddressByZipCode($event.target.value)" icon="o-map-pin" placeholder="Digite o CEP" x-mask="99999-999"  />
                        </div>
                        <div class="col-span-2">

                            <x-input label="Endereço" wire:model="addressState.address" placeholder="Rua, Avenida" />

                        </div>
                    </div>
                    <div class="grid gap-5 md:grid-cols-5">
                        <div class="col-span-1">

                            <x-input label="Número" wire:model="addressState.number" placeholder="123" />

                        </div>
                        <div class="col-span-2">

                            <x-input label="Bairro" phone wire:model="addressState.neighborhood" placeholder="Centro" />

                        </div>
                        <div class="col-span-2">

                            <x-input label="Cidade" phone wire:model="addressState.city" placeholder="Campo Florido" />

                        </div>
                        <div class="col-span-2">

                            <x-select label="Estado" :options="$states" option-value="acronym" option-label="name" placeholder-value="MG"
                                placeholder="Selecione um estado" wire:model="addressState.state" />

                        </div>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('patients.index') }}" />
                <x-button label="Atualizar" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
