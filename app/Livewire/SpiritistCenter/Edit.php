<?php

namespace App\Livewire\SpiritistCenter;

use App\Models\Address;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SpiritistCenter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Edit extends Component
{
    use Toast, WithFileUploads;

    public ?SpiritistCenter $spiritistCenter;
    public array $centerState = [];
    public array $addressState = [];
    public $logo = null;
    public Collection $states;


    public function mount()
    {
        $this->spiritistCenter = SpiritistCenter::with('address')->first();

        if (!empty($this->spiritistCenter)) {
            $this->centerState = $this->spiritistCenter->toArray();
        }

        if (!empty($this->spiritistCenter->address)) {
            $this->addressState = $this->spiritistCenter->address->toArray();
        }

        $this->states = $this->states();
    }


    public function render()
    {
        return view('livewire.spiritist-center.edit');
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->logo) {
            $url = $this->logo->store('spiritist-center', 'public');
            $validatedData['centerState']['logo_image_path'] = "/storage/$url";
        }

        $this->spiritistCenter = SpiritistCenter::updateOrCreate(['id' => $this->spiritistCenter->id ?? null], $validatedData['centerState']);

        if ($this->isAddressFilled()) {
            $address = Address::updateOrCreate(['id' => $this->spiritistCenter->address->id ?? null], $validatedData['addressState']);
            SpiritistCenter::where('id', $this->spiritistCenter->id)->update(['address_id' => $address->id]);
        }


        $this->success('Dados atualizados', description: 'Os dados do centro espírita foram atualizados.');
    }


    public function rules(): array
    {
        return [
            'centerState.name' => 'required|string|min:2|max:255',
            'centerState.email' => 'nullable|email',
            'centerState.phone' => 'nullable|string|min:10|max:20',
            'logo' => 'nullable|image|max:2048',
            'addressState.address' => ['nullable', 'string', 'min:2', 'max:255', Rule::requiredIf(!empty($this->addressState['number']) || !empty($this->addressState['neighborhood']) || !empty($this->addressState['city']) || !empty($this->addressState['state']) || !empty($this->addressState['zip_code']))],
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
            'centerState.name.required' => 'Informe o nome do Centro Espírita',
            'centerState.name.min' => 'O nome deve ter pelo menos 2 caracteres',
            'centerState.name.max' => 'O nome deve ter no maximo 255 caracteres',
            'centerState.email.email' => 'O e-mail informado não é valido.',
            'centerState.phone.min' => 'O telefone deve ter pelo menos 10 caracteres',
            'centerState.phone.max' => 'O telefone deve ter no maximo 20 caracteres',
            'logo.max' => 'O logo deve ter no maximo 2 MB',
            'logo.image' => 'O logo deve ser uma imagem',
            'addressState.zip_code.min' => 'O CEP deve ter pelo menos 8 caracteres',
            'addressState.zip_code.max' => 'O CEP deve ter no maximo 9 caracteres',
            'addressState.address.required' => 'Informe o endereço do assistido',
            'addressState.address.min' => 'O endereço deve ter pelo menos 2 caracteres',
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


    public function getAddressByZipCode($zipCode): void
    {
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);

        if (!empty($zipCode) && strlen($zipCode) === 8) {

            $response = Cache::remember('address_' . $zipCode, now()->addHours(48), function () use ($zipCode) {
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
