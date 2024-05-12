<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public $types_of_treatment;
    public $modes;
    public Collection $patients;

    public ?int $patient_id = null;
    public ?int $treatment_type_id = null;
    public string $treatment_mode = 'Presencial';
    public string $date;
    public ?string $notes = null;
    public ?string $who_requested_it = null;
    public ?string $who_requested_it_phone = null;

    public bool $createAppointmentModal = false;


    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $this->searchPatients();
    }


    public function render()
    {
        return view('livewire.appointments.create');
    }


    public function searchPatients(string $value = ''): void
    {
        $selectedOption = Patient::with('address')->where('id', $this->patient_id)->get();

        $this->patients = Patient::with('address')
            ->where('name', 'like', "%$value%")
            ->take(10)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }


    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->treatment_mode == 'Presencial') {
            $validatedData['status'] = 'Confirmado';
        } else {
            $validatedData['status'] = 'Em espera';
        }

        $appointment = Appointment::create($validatedData);

        $this->success('Agendamento adicionado.', description: "Assistido  {$appointment->patient->name}.");

        $this->dispatch('saved');
        $this->createAppointmentModal = false;
        $this->reset('patient_id', 'treatment_type_id', 'treatment_mode', 'date', 'notes', 'who_requested_it', 'who_requested_it_phone');
    }


    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'treatment_type_id' => ['required', 'exists:types_of_treatments,id'],
            'treatment_mode' => ['required', 'string', 'in:Presencial,A distância'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'who_requested_it' => ['nullable', 'string'],
            'who_requested_it_phone' => ['nullable', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'patient_id.required' => 'Selecione um assistido para este agendamento.',
            'treatment_type_id.required' => 'Selecione um tipo de atendimento',
            'treatment_mode.required' => 'Selecione o modo de atendimento.',
            'date.required' => 'Informe a data para este agendamento.',
        ];
    }
}
