<?php

namespace App\Livewire\Mentors;

use App\Models\Mentor;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    use Toast;

    public Mentor $mentor;
    public string $name;


    public function mount(): void
    {
        $this->fill($this->mentor);
    }


    #[Title('Editar Mentor')]
    public function render()
    {
        return view('livewire.mentors.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $this->mentor->update($validatedData);

        $this->success('Mentor atualizado.', description: "O mentor {$this->mentor->name} foi atualizado.", redirectTo: route('mentors.index'));
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('mentors', 'name')->ignore($this->mentor->id)],
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
