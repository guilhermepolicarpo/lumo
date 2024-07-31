<?php

namespace App\Livewire\Appointments;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Appointment;
use App\Models\TypeOfTreatment;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use Toast, WithPagination;

    public string $search = '';

    #[Session(key: 'dateFilter')]
    public string $date = '';

    #[Session(key: 'statusFilter')]
    public string $selectedStatus = '';

    #[Session(key: 'typeFilter')]
    public string $selectedType = '';

    #[Session(key: 'modeFilter')]
    public string $selectedMode = '';

    public ?int $idToDelete = null;
    public ?int $appointmentId = null;
    public ?Appointment $appointmentToView = null;

    // Modals
    public bool $appointmentViewModal = false;
    public bool $deleteModalConfirmation = false;
    public bool $receivePatientModalConfirmation = false;
    public bool $drawer = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    public function mount(): void
    {
        $this->date = session()->get('dateFilter') ?? now()->format('Y-m-d');
    }


    #[Title('Agendamentos')]
    public function render()
    {
        return view('livewire.appointments.index', [
            'appointments' => $this->appointments(),
            'headers' => $this->headers(),
            'types_of_treatment' => TypeOfTreatment::orderBy('name')->get(['id', 'name']),
            'modes' => $this->modes(),
            'status' => $this->status(),
        ]);
    }


    protected function appointments(): LengthAwarePaginator
    {
        return Appointment::with('patient', 'treatmentType')
            ->withAggregate('patient', 'name')
            ->withAggregate('treatmentType', 'name')
            ->when($this->search, fn (Builder $q) => $q
                ->whereRelation('patient', 'name', 'like', "%$this->search%"))
            ->when($this->date, fn (Builder $q) => $q->where('date', '=', $this->date))
            ->when($this->selectedStatus, fn (Builder $q) => $q->where('status', '=', $this->selectedStatus))
            ->when($this->selectedType, fn (Builder $q) => $q->where('treatment_type_id', '=', $this->selectedType))
            ->when($this->selectedMode, fn (Builder $q) => $q->where('treatment_mode', '=', $this->selectedMode))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(15);
    }


    public function getAppointment($id): void
    {
        $this->appointmentToView = Appointment::with('patient.address', 'treatmentType')->find($id);
    }


    public function receivePatient(Appointment $appointment): void
    {
        $appointment->status = 'Em espera';
        $appointment->save();

        $this->receivePatientModalConfirmation = false;
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


    // Table headers
    public function headers(): array
    {
        $headers = [
            ['key' => 'patient_name', 'label' => 'Assistido', 'class' => 'w-64  whitespace-nowrap'],
            ['key' => 'treatment_type_name', 'label' => 'Tipo de Atendimento', 'class' => 'whitespace-nowrap'],
            ['key' => 'treatment_mode', 'label' => 'Modo de Atendimento', 'class' => 'whitespace-nowrap'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'whitespace-nowrap'],
        ];

        if (empty($this->date)) {
            $newHeader = ['key' => 'date', 'label' => 'Data', 'class' => 'whitespace-nowrap'];
            array_splice($headers, 2, 0, [$newHeader]);
        }

        return $headers;
    }


    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }


    protected function modes(): array
    {
        return [
            ['id' => 'Presencial', 'name' => 'Presencial'],
            ['id' => 'A distância', 'name' => 'A distância'],
        ];
    }


    private function status(): array
    {
        return [
            ['id' => 'Confirmado', 'name' => 'Confirmado'],
            ['id' => 'Em espera', 'name' => 'Em espera'],
            ['id' => 'Atendido', 'name' => 'Atendido'],
            ['id' => 'Faltou', 'name' => 'Faltou'],
        ];
    }


    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->resetPage();
        $this->success('Filtros removidos.');
    }
}
