<?php

namespace App\Livewire\TypesOfTreatments;

use App\Models\TypeOfTreatment;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    use Toast;

    public string $name = '';
    public string $description = '';
    public bool $is_the_healing_touch = false;
    public bool $has_form = false;


    #[Title('Adicionar Tipo de Tratamento')]
    public function render()
    {
        return view('livewire.types-of-treatments.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $typeOfTreatment = TypeOfTreatment::create($validatedData);

        $this->success('Orientação adicionada.', description: "A orientação {$typeOfTreatment->name} foi adicionada.", redirectTo: route('types-of-treatments.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255|unique:types_of_treatments,name',
            'description' => 'nullable|string|min:2',
            'is_the_healing_touch' => 'required|boolean',
            'has_form' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do tipo de atendimento.',
            'name.unique' => 'Já existe um tipo de atendimento com este nome.',
        ];
    }
}
