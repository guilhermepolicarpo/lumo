<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{

    use Toast,WithPagination;

    public string $search = '';

    public ?int $patientIdToDelete = null;

    public bool $deleteModalConfirmation = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];



    #[Title('Assistidos')]
    public function render()
    {
        return view('livewire.patients.index', [
            'patients' => $this->patients(),
            'headers' => $this->headers()
        ]);
    }


    public function patients(): LengthAwarePaginator
    {
        return Patient::with('address')
            ->withAggregate('address', 'address')
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome', 'class' => 'w-64 h-16 whitespace-nowrap'],
            ['key' => 'address', 'label' => 'Endereço', 'sortBy' => 'address_address', 'class' => 'whitespace-nowrap'],
            ['key' => 'birth', 'label' => 'Idade', 'class' => 'whitespace-nowrap'],
            ['key' => 'phone', 'label' => 'Telefone', 'class' => 'whitespace-nowrap'],
        ];
    }


    // Delete action
    public function delete(Patient $patient): void
    {
        if ($patient->address) {
            $deleted = $patient->address()->delete();

        } else {
            $deleted = $patient->delete();
        }

        $this->deleteModalConfirmation = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar o assistido $patient->name.");
            return;
        }

        $this->warning("Assistido deletado.", "O assistido $patient->name foi deletado");
    }


    public function setPatientIdToDelete(int $id): void
    {
        $this->patientIdToDelete = $id;
    }


    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }
}
