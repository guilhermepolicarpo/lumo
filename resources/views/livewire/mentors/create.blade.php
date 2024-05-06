<div>
    {{-- HEADER --}}
    <x-header title="Adicionar mentor" size="text-2xl" progress-indicator />

    <x-card class="max-w-xl">
        <x-form wire:submit="save">

            <x-input label="Nome" wire:model="name" icon="o-user" placeholder="João Silva" />

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('mentors.index') }}" />
                <x-button label="Adicionar" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
