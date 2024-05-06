<?php

namespace App\Livewire\Mentors;

use App\Models\Mentor;
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
    public ?int $mentorIdToDelete = null;
    public bool $deleteModalConfirmation = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];


    #[Title('Mentores')]
    public function render()
    {
        return view('livewire.mentors.index', [
            'mentors' => $this->mentors(),
            'headers' => $this->headers()
        ]);
    }


    public function mentors(): LengthAwarePaginator
    {
        return Mentor::query()
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }


    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Nome', 'class' => 'h-16 whitespace-nowrap'],
        ];
    }


    // Delete action
    public function delete(Mentor $mentor): void
    {
        $deleted = $mentor->delete();

        $this->deleteModalConfirmation = false;

        if (!$deleted) {
            $this->error("Ocorreu um erro.", "Não foi possível deletar o mentor $mentor->name.");
            return;
        }

        $this->warning("Mentor deletado.", "O mentor $mentor->name foi deletado");
    }


    public function setMentorIdToDelete(int $id): void
    {
        $this->mentorIdToDelete = $id;
    }


    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }
}
