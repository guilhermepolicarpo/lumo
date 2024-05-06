<?php

namespace App\Livewire\Orientations;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Orientation;
use Livewire\Attributes\Title;

class Create extends Component
{
    use Toast;

    public string $name;
    public string $description;


    #[Title('Adicionar Orientação')]
    public function render()
    {
        return view('livewire.orientations.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $orientation = Orientation::create($validatedData);

        $this->success('Orientação adicionada.', description: "A orientação {$orientation->name} foi adicionada.", redirectTo: route('orientations.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255|unique:orientations,name',
            'description' => 'nullable|string|min:2',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da orientação.',
            'name.unique' => 'Já existe uma orientação com este nome.',
        ];
    }
}
