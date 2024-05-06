<?php

namespace App\Livewire\Medicines;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Medicine;
use Livewire\Attributes\Title;

class Edit extends Component
{
    use Toast;

    public Medicine $medicine;
    public string $name;
    public string $description;


    public function mount(): void
    {
        $this->fill($this->medicine);
    }


    #[Title('Editar Fluídico')]
    public function render()
    {
        return view('livewire.medicines.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $this->medicine->update($validatedData);

        $this->success('Fluido editado!', description: "O fluiddo {$this->medicine->name} foi editado.", redirectTo: route('medicines.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255|unique:medicines,name,' . $this->medicine->id,
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
