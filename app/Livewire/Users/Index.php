<?php

namespace App\Livewire\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public string $search = '';

    public ?int $userIdToDelete = null;

    public bool $deleteUserModal = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.users.index', [
            'users' => $this->users(),
            'headers' => $this->headers()
        ]);
    }


    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%$this->search%")
                ->where('id', '!=', auth()->id())
                ->orWhere('email', 'like', "%$this->search%"))
            ->where('id', '!=', auth()->id())
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome', 'class' => 'w-64 whitespace-nowrap'],
            ['key' => 'email', 'label' => 'E-mail'],
        ];
    }


    // Delete action
    public function delete(User $user): void
    {
        if ($user->id == auth()->id()) {
            $this->error("Não foi possível deletar", "Não é permitido deletar o seu próprio perfil");
            return;
        }

        $deleted = $user->delete();

        $this->deleteUserModal = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar o usuário $user->name.");
            return;
        }

        $this->warning("Usuário deletado.", "O usuário $user->name foi deletado");
    }


    public function setUserIdToDelete(int $id): void
    {
        $this->userIdToDelete = $id;
    }


    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }
}
