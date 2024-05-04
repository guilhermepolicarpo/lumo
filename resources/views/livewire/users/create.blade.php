<div>
    <!-- HEADER -->
    <x-header title="Adicionar usuário" progress-indicator />

    <x-card class="max-w-4xl">
        <x-form wire:submit="save">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input label="Nome" wire:model="name" icon="o-user" placeholder="Digite o nome" />
                <x-input label="E-mail" wire:model="email" icon="o-envelope" placeholder="Digite o e-mail" />
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <x-input label="Senha" wire:model="password" type="password" icon="o-key" placeholder="Digite a senha" />
                <x-input label="Confirmar Senha" wire:model="password_confirmation" type="password" icon="o-key" placeholder="Digite a senha novamente" />
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" link="{{ route('users.index') }}" />
                <x-button label="Adicionar" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>

        </x-form>
    </x-card>


</div>
