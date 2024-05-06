<?php

namespace App\Livewire\TypesOfTreatments;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TypeOfTreatment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use Toast, WithPagination;

    public string $search = '';
    public ?int $idToDelete = null;
    public bool $deleteModalConfirmation = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    #[Title('Tipos de Atendimento')]
    public function render()
    {
        return view('livewire.types-of-treatments.index', [
            'typesOfTreatments' => $this->typesOfTreatment(),
            'headers' => $this->headers(),
        ]);
    }


    public function typesOfTreatment(): LengthAwarePaginator
    {
        return TypeOfTreatment::query()
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome', 'class' => 'w-64 h-16'],
            ['key' => 'description', 'label' => 'Descricão'],
        ];
    }


    // Delete action
    public function delete(TypeOfTreatment $typeOfTreatment): void
    {
        $deleted = $typeOfTreatment->delete();

        $this->deleteModalConfirmation = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar o tipo de atendimento $typeOfTreatment->name.");
            return;
        }

        $this->warning("Tipo de Atendimento deletado.", "O Tipo de Atendimento $typeOfTreatment->name foi deletado.");
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
}
