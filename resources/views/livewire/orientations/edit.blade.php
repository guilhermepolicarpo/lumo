<div>
    {{-- HEADER --}}
    <x-header title="Editar orientação" size="text-2xl" progress-indicator />

    <x-card class="max-w-xl">
        <x-form wire:submit="save">

            <x-input label="Nome" wire:model="name" icon="o-document-text" placeholder="João Silva" />

            <x-textarea label="Descrição" wire:model="description" placeholder="Digite uma descrição..." rows="5" />

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('orientations.index') }}" />
                <x-button label="Editar" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
