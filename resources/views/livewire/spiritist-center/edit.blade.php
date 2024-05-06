<div>
    {{-- HEADER --}}
    <x-header title="Centro Espírita" size="text-2xl" progress-indicator />

    <x-card>
        <x-form wire:submit="save">

            {{-- Logo section --}}
            <div class="grid-cols-6 lg:grid">
                <div class="col-span-2">
                    <x-header title="Logomarca" subtitle="Insira o logo da casa espírita"
                        size="text-1xl" />
                </div>
                <div class="grid col-span-4 gap-3">
                    <x-file
                        label="Logo"
                        wire:model="logo"
                        accept="image/png, image/jpeg"
                        crop-after-change
                        change-text="Alterar logo"
                        crop-text="Cortar"
                        hint="Clique para alterar"
                        crop-title-text="Cortar imagem"
                        crop-cancel-text="Manter original"
                        crop-save-text="Cortar" >

                        <img src="{{ $spiritistCenter->logo_url ?? '/empty-logo-image.png' }}" class="h-40 rounded-lg" />
                    </x-file>
                </div>
            </div>


            {{-- Basic section --}}
            <hr class="my-5" />

            <div class="grid-cols-6 lg:grid">
                <div class="col-span-2">
                    <x-header title="Informações da casa" subtitle="Preencha informações básicas da casa espírita"
                        size="text-1xl" />
                </div>
                <div class="grid col-span-4 gap-3">
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <x-input label="Nome do Centro" wire:model="centerState.name" icon="o-user"
                                placeholder="Casa Espírita..." />
                        </div>
                        <x-input id="phone" label="Telefone" wire:model="centerState.phone" icon="o-phone"
                        x-mask:dynamic="$input.length < 15 ? '(99) 9999-9999' : '(99) 99999-9999'"
                        placeholder="(00) 00000-0000" />
                    </div>
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <x-input label="E-mail" wire:model="centerState.email" icon="o-envelope" placeholder="email@exemplo.com" />
                        </div>
                    </div>
                </div>
            </div>


            {{-- Details section --}}
            <hr class="my-5" />

            <div class="grid-cols-6 lg:grid">
                <div class="md:col-span-2">
                    <x-header title="Endereço" subtitle="Preencha o endereço da casa espírita" size="text-1xl" />
                </div>
                <div class="grid col-span-4 gap-3">
                    <div class="grid grid-cols-2 gap-5 md:grid-cols-3">
                        <div class="col-span-1 md:col-span-1">

                            <x-input label="CEP" wire:model="addressState.zip_code"
                                x-on:blur="$wire.getAddressByZipCode($event.target.value)" icon="o-map-pin"
                                placeholder="Digite o CEP" x-mask="99999-999" />
                        </div>
                        <div class="col-span-2">

                            <x-input label="Endereço" wire:model="addressState.address" placeholder="Rua, Avenida" />
                            <div class="mt-1 text-sm" wire:loading wire:target='getAddressByZipCode'>Carregando
                                endereço...</div>

                        </div>
                    </div>
                    <div class="grid grid-cols-5 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <x-input label="Número" wire:model="addressState.number" placeholder="123" />
                        </div>
                        <div class="col-span-3 md:col-span-2">
                            <x-input label="Bairro" phone wire:model="addressState.neighborhood" placeholder="Centro" />
                        </div>
                        <div class="col-span-5 md:col-span-2">
                            <x-input label="Cidade" phone wire:model="addressState.city" placeholder="Campo Florido" />
                        </div>
                        <div class="col-span-5 md:col-span-2">
                            <x-select label="Estado" :options="$states" option-value="acronym" option-label="name"
                                placeholder-value="MG" placeholder="Selecione um estado"
                                wire:model="addressState.state" />
                        </div>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Salvar" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
