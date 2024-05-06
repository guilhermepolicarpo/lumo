<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    use Toast;

    public string $name;
    public string $description;


    #[Title('Adicionar fluídico')]
    public function render()
    {
        return view('livewire.medicines.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $medicine = Medicine::create($validatedData);

        $this->success('Fluido adicionado!', description: "O fluiddo {$medicine->name} foi adicionado.", redirectTo: route('medicines.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255|unique:medicines,name',
            'description' => 'nullable|string|min:2',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do fluídico.',
            'name.unique' => 'Já existe um fluídico com este nome.',
        ];
    }
}
