<div>
    {{-- HEADER --}}
    <x-header title="Adicionar fluídico" size="text-2xl" progress-indicator />

    <x-card class="max-w-xl">
        <x-form wire:submit="save">

            <x-input label="Nome" wire:model="name" icon="o-document-text" placeholder="Coração" />

            <x-textarea label="Descrição" wire:model="description"
                placeholder="Digite uma descrição para este fluídico..." rows="5" />

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('medicines.index') }}" class="text-base" />
                <x-button label="Adicionar" icon="o-paper-airplane" spinner="save" type="submit"
                    class="text-base btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
