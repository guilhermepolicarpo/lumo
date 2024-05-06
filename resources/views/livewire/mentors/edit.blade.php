<div>
    {{-- HEADER --}}
    <x-header title="Editar mentor" size="text-2xl" progress-indicator />

    <x-card class="max-w-xl">
        <x-form wire:submit="save">

            <x-input label="Nome" wire:model="name" icon="o-user" placeholder="João Silva" />

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('mentors.index') }}" class="text-base" />
                <x-button label="Editar" icon="o-paper-airplane" spinner="save" type="submit" class="text-base btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
