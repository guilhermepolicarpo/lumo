<?php

namespace App\Livewire\Users;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    use Toast;

    public User $user;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';


    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => 'string|min:8|max:30|confirmed',
            'password_confirmation' => 'string|min:8|max:30|same:password',
        ];
    }


    public function mount(): void
    {
        if ($this->user == auth()->user()) {
            redirect()->route('users.index');
        }

        $this->fill($this->user);
    }


    #[Title('Editar Usuário')]
    public function render()
    {
        return view('livewire.users.edit');
    }


    public function save(): void
    {
        if ($this->user == auth()->user()) {
            $this->error("Não é possível alterar o seu própio perfil.");
            return;
        }

        $validatedData = $this->validate();

        $updated = $this->user->update($validatedData);

        if (!$updated) {
            $this->error("Ocorreu um erro.", "Não foi possível atualizar o usuário {$this->user->name}.");
            return;
        }

        $this->success('Usuário atualizado.', description: "O usuário {$this->user->name} foi atualizado.", redirectTo: route('users.index'));
    }
}
