<?php

namespace App\Livewire\Orientations;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Orientation;
use Livewire\Attributes\Title;

class Edit extends Component
{
    use Toast;

    public Orientation $orientation;
    public string $name;
    public string $description;


    public function mount(): void
    {
        $this->fill($this->orientation);
    }


    #[Title('Editar Orientação')]
    public function render()
    {
        return view('livewire.orientations.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $this->orientation->update($validatedData);

        $this->success('Orientação atualizada!', description: "A orientação {$this->orientation->name} foi atualizada.", redirectTo: route('orientations.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:orientations,name,' . $this->orientation->id,
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
