<?php

namespace App\Livewire\Patients;

use Mary\Traits\Toast;
use App\Models\Address;
use App\Models\Patient;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Create extends Component
{
    use Toast;

    public array $patient;
    public array $address;
    public Collection $states;


    public function mount(): void
    {
        $this->states = $this->states();
    }


    #[Title('Adicionar Assistido')]
    public function render()
    {
        return view('livewire.patients.create');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        $addressData = $validatedData['address'] ?? null;
        $patientData = $validatedData['patient'] ?? null;

        try {
            DB::beginTransaction();

            $address = null;
            if (!empty($addressData) && $addressData['zip_code'] !== '') {
                $address = Address::create($addressData);
                if (!$address) {
                    throw new \Exception("Não foi possível adicionar o endereço do assistido {$this->name}.");
                }
            }

            $patient = $address ? $address->patient()->create($patientData) : Patient::create($patientData);
            if (!$patient) {
                throw new \Exception("Não foi possível adicionar o assistido {$this->name}.");
            }

            DB::commit();

            $this->success('Assistido adicionado.', description: "O assistido {$patient->name} foi adicionado.", redirectTo: route('patients.index'));
            
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error("Ocorreu um erro.", $e->getMessage());
        }
    }



    public function rules(): array
    {
        return [
            'patient.name' => 'required|string|min:3|max:255',
            'patient.email' => ['nullable', 'email', Rule::unique('patients', 'email')],
            'patient.birth' => 'required|date|before:today|after:1900-01-01',
            'patient.phone' => 'required|string|min:10|max:20',
            'address.address' => 'nullable|string|min:3|max:255',
            'address.number' => 'nullable|string|min:1|max:255',
            'address.neighborhood' => 'nullable|string|min:2|max:255',
            'address.city' => 'nullable|string|min:3|max:255',
            'address.state' => 'nullable|string|min:2|max:2',
            'address.zip_code' => 'nullable|string|min:8|max:9',
        ];
    }

    public function messages(): array
    {
        return [
            'patient.name.required' => 'Informe o nome do assistido',
            'patient.name.min' => 'O nome deve ter pelo menos 2 caracteres',
            'patient.name.max' => 'O nome deve ter no maximo 255 caracteres',
            'patient.email.email' => 'O e-mail informado não é valido.',
            'patient.email.unique' => 'O e-mail informado ja existe.',
            'patient.birth.required' => 'Informe a data de nascimento do assistido',
            'patient.birth.before' => 'A data de nascimento deve ser anterior a data atual',
            'patient.birth.date' => 'A data de nascimento deve ser uma data',
            'patient.birth.after' => 'A data de nascimento deve ser posterior a 01/10/1900',
            'patient.phone.required' => 'Informe o telefone do assistido',
            'patient.phone.min' => 'O telefone deve ter pelo menos 10 caracteres',
            'patient.phone.max' => 'O telefone deve ter no maximo 20 caracteres',
            'address.address.min' => 'O endereço deve ter pelo menos 3 caracteres',
            'address.address.max' => 'O endereço deve ter no maximo 255 caracteres',
            'address.number.min' => 'O numero deve ter pelo menos 1 caractere',
            'address.number.max' => 'O numero deve ter no maximo 255 caracteres',
            'address.neighborhood.min' => 'O bairro deve ter pelo menos 3 caracteres',
            'address.neighborhood.max' => 'O bairro deve ter no maximo 255 caracteres',
            'address.city.min' => 'A cidade deve ter pelo menos 3 caracteres',
            'address.city.max' => 'A cidade deve ter no maximo 255 caracteres',
            'address.state.min' => 'O estado deve ter pelo menos 2 caracteres',
            'address.state.max' => 'O estado deve ter no maximo 2 caracteres',
        ];
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

            $this->address['address'] = $response['logradouro'];
            $this->address['neighborhood'] = $response['bairro'];
            $this->address['state'] = $response['uf'];
            $this->address['city'] = $response['localidade'];
        }
    }
}
