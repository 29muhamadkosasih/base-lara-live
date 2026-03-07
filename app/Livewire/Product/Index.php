<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Product;

#[Layout('layouts.app')]
#[Title('Produk')]
class Index extends Component
{
    use WithPagination;

    public $name, $detail, $dataId;
    public $isEdit = false;
    public $showConfirm = false;

    // filter/search manual
    public $search = '';
    public $filter = '';

    protected $paginationTheme = 'bootstrap';

    /** ==========================
     *  FILTER & SEARCH
     *  ========================== */
    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function applyFilter()
    {
        $this->filter = $this->search;
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['search', 'filter']);
        $this->resetPage();
    }

    /** ==========================
     *  RENDER VIEW
     *  ========================== */
    public function render()
    {
        $datas = Product::query()->when($this->filter, fn ($q) => $q->where('name', 'like', '%' . $this->filter . '%'))->latest()->paginate(10);

        return view('livewire.product.index', compact('datas'));
    }

    /** ==========================
     *  FORM ACTION
     *  ========================== */
    public function resetInput()
    {
        $this->reset(['name', 'detail', 'dataId', 'isEdit']);
        $this->resetValidation();
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type, message: $message);
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
        ]);

        Product::create($validated);

        $this->toast('success', 'Data berhasil ditambahkan.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $data = Product::findOrFail($id);
        $this->dataId = $data->id;
        $this->name = $data->name;
        $this->detail = $data->detail;
        $this->isEdit = true;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($this->dataId);
        $product->update($validated);

        $this->toast('success', 'Data berhasil diperbarui.');
        $this->resetInput();
    }

    /** ==========================
     *  DELETE HANDLING
     *  ========================== */
    public function confirmDelete($id)
    {
        $this->dataId = $id;
        $this->showConfirm = true;
    }

    public function closeModal()
    {
        $this->showConfirm = false;
        $this->dataId = null;
    }

    public function delete()
    {
        Product::find($this->dataId)?->delete();

        $this->toast('success', 'Data berhasil dihapus.');
        $this->closeModal();
        $this->resetInput();
    }
}
