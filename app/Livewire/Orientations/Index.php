<?php

namespace App\Livewire\Orientations;

use App\Models\Orientation;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use Toast, WithPagination;

    public string $search = '';
    public ?int $idToDelete = null;
    public bool $deleteModalConfirmation = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    #[Title('Orientações')]
    public function render()
    {
        return view('livewire.orientations.index', [
            'orientations' => $this->orientations(),
            'headers' => $this->headers()
        ]);
    }


    public function orientations(): LengthAwarePaginator
    {
        return Orientation::query()
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
    public function delete(Orientation $orientation): void
    {
        $deleted = $orientation->delete();

        $this->deleteModalConfirmation = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar a orientação $orientation->name.");
            return;
        }

        $this->warning("Orientação deletada.", "A orientação $orientation->name foi deletada.");
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
