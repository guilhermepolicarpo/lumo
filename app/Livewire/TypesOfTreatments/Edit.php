<?php

namespace App\Livewire\TypesOfTreatments;

use App\Models\TypeOfTreatment;
use Livewire\Component;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public TypeOfTreatment $typeOfTreatment;
    public string $name;
    public string $description;
    public bool $is_the_healing_touch;
    public bool $has_form;


    public function mount(): void
    {
        $this->fill($this->typeOfTreatment);
    }


    #[Title('Editar Tipo de Tratamento')]
    public function render()
    {
        return view('livewire.types-of-treatments.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $this->typeOfTreatment->update($validatedData);

        $this->success('Tipo de atendimento atualizado!', description: "O tipo de atendimento {$this->typeOfTreatment->name} foi atualizado.", redirectTo: route('types-of-treatments.index'));
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:types_of_treatments,name,' . $this->typeOfTreatment->id,
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
