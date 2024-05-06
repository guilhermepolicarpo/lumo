<?php

namespace App\Livewire\Mentors;

use App\Models\Mentor;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    use Toast;

    public string $name;


    #[Title('Adicionar Mentor')]
    public function render()
    {
        return view('livewire.mentors.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $mentor = Mentor::create($validatedData);

        $this->success('Mentor adicionado.', description: "O mentor {$mentor->name} foi adicionado.", redirectTo: route('mentors.index'));
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:mentors,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do mentor é obrigatório.',
            'name.string' => 'O nome do mentor deve ser uma string.',
            'name.max' => 'O nome do mentor deve ter no máximo 255 caracteres.',
            'name.unique' => 'Já existe um mentor cadastrado com este nome.',
        ];
    }
}
