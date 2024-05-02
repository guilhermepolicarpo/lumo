<?php

namespace App\Livewire\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use Toast;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:8|confirmed')]
    public string $password = '';

    #[Validate('required|min:8|same:password')]
    public string $password_confirmation = '';

    
    #[Title('Novo Usuário')]
    public function render()
    {
        return view('livewire.users.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $userCreated = User::create($validatedData);

        if (!$userCreated) {
            $this->error("Ocorreu um erro.", "Não foi possível adicionar o usuário {$this->name}.");
            return;
        }

        $this->success('Usuário adicionado.', description: "O usuário $userCreated->name foi adicionado.", redirectTo: route('users.index'));
    }
}
