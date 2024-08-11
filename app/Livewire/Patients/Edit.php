<?php

namespace App\Livewire\Patients;

use Mary\Traits\Toast;
use App\Models\Patient;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Edit extends Component
{
    use Toast;

    public Patient $patient;
    public ?int $patientIdToDelete;
    public array $patientState;
    public array $addressState;
    public Collection $states;
    public bool $deleteModalConfirmation = false;


    public function mount(): void
    {
        $this->patientState = $this->patient->toArray();

        if (!empty($this->patient->address)) {
            $this->addressState = $this->patient->address()->first()->toArray();
        }
        $this->states = $this->states();
    }


    #[Title('Editar Assistido')]
    public function render()
    {
        return view('livewire.patients.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $addressData = $validatedData['addressState'] ?? null;
        $patientData = $validatedData['patientState'] ?? null;

        try {
            DB::beginTransaction();

            if ($this->isAddressFilled()) {
                if ($this->patient->address) {
                    $address = $this->patient->address->update($addressData);
                } else {
                    $address = $this->patient->address()->create($addressData);
                    $this->patient->address()->associate($address);
                }

                if (!$address) {
                    throw new \Exception("Não foi possível adicionar o endereço do assistido {$this->patient->name}.");
                }
            }

            $this->patient->update($patientData);

            DB::commit();

            $this->success('Assistido atualizado.', description: "O assistido {$this->patient->name} foi atualizado.", redirectTo: route('patients.index'));

        } catch (\Exception $e) {
            DB::rollBack();

            $this->error("Ocorreu um erro.", $e->getMessage());
        }
    }


    public function rules(): array
    {
        return [
            'patientState.name' => 'required|string|min:3|max:255',
            'patientState.email' => ['nullable', 'email', Rule::unique('patients', 'email')->ignore($this->patient->id)],
            'patientState.birth' => 'required|date|before:today|after:1900-01-01',
            'patientState.phone' => 'required|string|min:10|max:20',
            'addressState.address' => ['nullable', 'string', 'min:3', 'max:255', Rule::requiredIf(!empty($this->addressState['number']) || !empty($this->addressState['neighborhood']) || !empty($this->addressState['city']) || !empty($this->addressState['state']) || !empty($this->addressState['zip_code']))],
            'addressState.number' => 'nullable|string|min:1|max:255',
            'addressState.neighborhood' => 'nullable|string|min:2|max:255',
            'addressState.city' => 'nullable|string|min:3|max:255',
            'addressState.state' => 'nullable|string|min:2|max:2',
            'addressState.zip_code' => 'nullable|string|min:8|max:9',
        ];
    }

    public function messages(): array
    {
        return [
            'patientState.name.required' => 'Informe o nome do assistido',
            'patientState.name.min' => 'O nome deve ter pelo menos 2 caracteres',
            'patientState.name.max' => 'O nome deve ter no maximo 255 caracteres',
            'patientState.email.email' => 'O e-mail informado não é valido.',
            'patientState.email.unique' => 'O e-mail informado ja existe.',
            'patientState.birth.required' => 'Informe a data de nascimento do assistido',
            'patientState.birth.before' => 'A data de nascimento deve ser anterior a data atual',
            'patientState.birth.date' => 'A data de nascimento deve ser uma data',
            'patientState.birth.after' => 'A data de nascimento deve ser posterior a 01/10/1900',
            'patientState.phone.required' => 'Informe o telefone do assistido',
            'patientState.phone.min' => 'O telefone deve ter pelo menos 10 caracteres',
            'patientState.phone.max' => 'O telefone deve ter no maximo 20 caracteres',
            'addressState.address.required' => 'Informe o endereço do assistido',
            'addressState.address.min' => 'O endereço deve ter pelo menos 3 caracteres',
            'addressState.address.max' => 'O endereço deve ter no maximo 255 caracteres',
            'addressState.number.min' => 'O numero deve ter pelo menos 1 caractere',
            'addressState.number.max' => 'O numero deve ter no maximo 255 caracteres',
            'addressState.neighborhood.min' => 'O bairro deve ter pelo menos 3 caracteres',
            'addressState.neighborhood.max' => 'O bairro deve ter no maximo 255 caracteres',
            'addressState.city.min' => 'A cidade deve ter pelo menos 3 caracteres',
            'addressState.city.max' => 'A cidade deve ter no maximo 255 caracteres',
            'addressState.state.min' => 'O estado deve ter pelo menos 2 caracteres',
            'addressState.state.max' => 'O estado deve ter no maximo 2 caracteres',
        ];
    }


    // Delete action
    public function delete(): void
    {
        try {
            DB::beginTransaction();

            if ($this->patient->address) {
                $this->patient->address()->delete();
            } else {
                $this->patient->delete();
            }

            $this->deleteModalConfirmation = false;

            DB::commit();

            $this->warning("Assistido deletado.", "O assistido {$this->patient->name} foi deletado", redirectTo: route('patients.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteModalConfirmation = false;
            $this->error("Ocorreu um erro.", "Não foi possível deletar o assistido {$this->patient->name}, pois já existem atendimentos registrados para ele.");
        }
    }


    public function getAddressByZipCode($zipCode): void
    {
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);

        if (!empty($zipCode) && strlen($zipCode) === 8) {

            $response = Cache::remember('address_' . $zipCode, now()->addHours(24), function () use ($zipCode) {
                return Http::get('https://viacep.com.br/ws/' . $zipCode . '/json/')->json();
            });

            if (isset($response['erro']) || !$response) {
                return;
            }

            $this->addressState['address'] = $response['logradouro'];
            $this->addressState['neighborhood'] = $response['bairro'];
            $this->addressState['state'] = $response['uf'];
            $this->addressState['city'] = $response['localidade'];
        }
    }


    private function states(): Collection
    {
        $states = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapa',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceara',
            'DF' => 'Distrito Federal',
            'ES' => 'Espirito Santo',
            'GO' => 'Goias',
            'MA' => 'Maranhao',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Para',
            'PB' => 'Paraiba',
            'PR' => 'Parana',
            'PE' => 'Pernambuco',
            'PI' => 'Piaui',
            'RJ' => 'Rio de Janeiro',
            'RS' => 'Rio Grande do Sul',
            'RN' => 'Rio Grande do Norte',
            'RO' => 'Rondonia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'Sao Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
            'EX' => 'Estrangeiro',
        ];

        foreach ($states as $acronym => $name) {
            $statesWithKey[] = [
                'acronym' => $acronym,
                'name' => $name
            ];
        }

        return collect($statesWithKey);
    }


    private function isAddressFilled(): bool
    {
        foreach ($this->addressState as $field) {
            if (!empty($field)) {
                return true;
            }
        }

        return false;
    }
}
