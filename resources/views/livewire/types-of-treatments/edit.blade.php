<div>
    {{-- HEADER --}}
    <x-header title="Editar Tipo de Atendimento" size="text-2xl" progress-indicator />

    <x-card class="max-w-xl">
        <x-form wire:submit="save">

            <x-input label="Nome" wire:model="name" icon="o-document-text" placeholder="Passe com todos" />

            <x-textarea label="Descrição" wire:model="description" placeholder="Digite uma descrição..." rows="5" />

            <x-toggle label="Este atendimento é um tipo de passe?" wire:model="is_the_healing_touch" />

            <x-toggle label="Habilitar formulário para este atendimento?" wire:model="has_form" />

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('types-of-treatments.index') }}" class="text-base" />
                <x-button label="Editar" icon="o-paper-airplane" spinner="save" type="submit"
                    class="text-base btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
