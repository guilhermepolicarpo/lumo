<?php

namespace App\Livewire\Patients;

use Mary\Traits\Toast;
use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{

    use Toast, WithPagination;

    public string $search = '';

    public ?int $patientIdToDelete = null;

    public bool $deleteModalConfirmation = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    #[Session('patientsPerPage')]
    public int $perPage = 10;



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
        return Patient::select('id', 'name', 'email', 'phone', 'birth', 'address_id')
            ->with('address')
            ->withAggregate('address', 'address')
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'address', 'label' => 'Endereço', 'sortBy' => 'address_address', 'class' => 'whitespace-nowrap'],
            ['key' => 'birth', 'label' => 'Idade', 'class' => 'whitespace-nowrap'],
        ];
    }


    // Delete action
    public function delete(Patient $patient): void
    {
        try {
            DB::beginTransaction();

            if ($patient->address) {
                $deleted = $patient->address()->delete();
            } else {
                $patient->delete();
            }

            $this->deleteModalConfirmation = false;

            DB::commit();

            $this->warning("Assistido deletado.", "O assistido $patient->name foi deletado");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteModalConfirmation = false;
            $this->error("Ocorreu um erro.", "Não foi possível deletar o assistido $patient->name, pois já existem atendimentos registrados para ele.");
        }
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
