<div>
    <!-- HEADER -->
    <x-header title="Usuários" size="text-3xl" progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce.250ms="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Adicionar usuário" link="/users/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>

    </x-header>

    <!-- TABLE  -->
    <x-card>
        @if ($users->count() == 0)
            <p>Nenhum usuario encontrado.</p>
        @else
            <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" link="users/{id}/edit" with-pagination>
                @scope('actions', $user)
                <div class="flex">
                    <x-button icon="o-pencil-square" link="{{ route('users.edit', $user) }}" spinner class="px-2 text-indigo-500 btn-ghost btn-sm" />
                    <x-button icon="o-trash" @click="$wire.deleteUserModal = true" wire:click="setUserIdToDelete({{ $user['id'] }})" spinner class="px-2 text-red-500 btn-ghost btn-sm" />
                </div>
                @endscope
            </x-table>
        @endif
    </x-card>


    <x-modal wire:model="deleteUserModal" title="Tem certeza?">
        <p>Tem certeza que deseja excluir este usuário?</p>

        <x-slot:actions>
            <x-button label="Cancelar" @click="$wire.deleteUserModal = false" />
            <x-button label="Excluir" wire:click="delete({{ $userIdToDelete }})" class="bg-red-500 border-red-500 hover:bg-red-600 hover:border-red-600 btn-primary" />
        </x-slot:actions>
    </x-modal>
</div>
