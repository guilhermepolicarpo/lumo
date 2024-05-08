<?php

namespace App\Livewire\Appointments;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use Toast, WithPagination;

    public string $search = '';

    public $date;

    public ?int $idToDelete = null;

    public bool $deleteModalConfirmation = false;

    public bool $drawer = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    public function mount(): void
    {
        $this->date = date('Y-m-d');
    }


    #[Title('Agendamentos')]
    public function render()
    {
        return view('livewire.appointments.index', [
            'appointments' => $this->appointments(),
            'headers' => $this->headers(),
        ]);
    }


    public function appointments(): LengthAwarePaginator
    {
        return Appointment::query()
            ->withAggregate('patient', 'name')
            ->withAggregate('treatmentType', 'name')
            ->when($this->search, fn (Builder $q) => $q
                ->whereRelation('patient', 'name', 'like', "%$this->search%"))
            ->when($this->date, fn (Builder $q) => $q->where('date', '=', $this->date))
            ->orderBy(...array_values($this->sortBy))
                ->paginate(10);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'patient_name', 'label' => 'Assistido', 'class' => 'w-64 h-16 whitespace-nowrap'],
            ['key' => 'treatment_type_name', 'label' => 'Tipo de Atendimento', 'class' => 'whitespace-nowrap'],
            ['key' => 'date', 'label' => 'Data', 'class' => 'whitespace-nowrap'],
            ['key' => 'treatment_mode', 'label' => 'Modo de Atendimento', 'class' => 'whitespace-nowrap'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'whitespace-nowrap'],
        ];
    }


    // Delete action
    public function delete(Appointment $appointment): void
    {
        $deleted = $appointment->delete();

        $this->deleteModalConfirmation = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar o agendamento do assistido {$appointment->patient->name}.");
            return;
        }

        $this->warning("Agendamento deletado.", "O agendamento do assistido {$appointment->patient->name} foi deletado");
    }


    public function setIdToDelete(int $id): void
    {
        $this->idToDelete = $id;
    }


    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }


    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->resetPage();
        $this->success('Filtros removidos.');
    }
}
