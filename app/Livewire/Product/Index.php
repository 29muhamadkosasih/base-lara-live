<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Support\AuditLogger;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Produk')]
class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $detail, $dataId, $cover_image;
    public $isEdit = false;
    public $showConfirm = false;

    // filter/search manual
    public $search = '';
    public $filter = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $validationAttributes = [
        'name' => 'Nama Produk',
        'detail' => 'Detail Produk',
        'cover_image' => 'Cover Image',
    ];

    /** ==========================
     *  VALIDATION RULES
     *  ========================== */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /** ==========================
     *  FILTER & SEARCH
     *  ========================== */
    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    public function updatePerPage($value)
    {
        $this->perPage = $value === 'all' ? PHP_INT_MAX : (int) $value;
        $this->resetPage();
    }

    /** ==========================
     *  RENDER VIEW
     *  ========================== */
    public function render()
    {
        $datas = Product::query()->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))->latest()->paginate($this->perPage);

        return view('livewire.product.index', compact('datas'));
    }

    /** ==========================
     *  FORM ACTION
     *  ========================== */
    public function resetInput()
    {
        $this->reset(['name', 'detail', 'cover_image', 'dataId', 'isEdit']);
        $this->resetValidation();
    }

    protected function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
        $this->dispatch('notify', type: $type, message: $message);
    }

    /**
     * Handle cover image upload
     *
     * @return string|null
     */
    private function handleImageUpload(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        $filename = time() . '-' . $this->cover_image->getClientOriginalName();
        $destinationPath = public_path('storage/uploads/cover_images');

        // Pastikan folder tujuan ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Copy file dari temporary location ke destination
        $tempPath = $this->cover_image->getRealPath();
        copy($tempPath, $destinationPath . '/' . $filename);

        // Auto delete file temporary setelah berhasil di-copy
        $this->cover_image->delete();

        return $filename;
    }

    /**
     * Delete image file from storage
     *
     * @param string $filename
     * @return void
     */
    private function deleteImageFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $filepath = public_path('storage/uploads/cover_images/' . $filename);
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    public function store()
    {
        $validated = $this->validate($this->rules());

        // Handle cover image upload
        if ($coverImage = $this->handleImageUpload()) {
            $validated['cover_image'] = $coverImage;
        }

        $product = Product::create($validated);
        AuditLogger::log('product.created', $product, 'Produk baru ditambahkan.', [
            'attributes' => $validated,
        ]);

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
        $validated = $this->validate($this->rules());

        $product = Product::findOrFail($this->dataId);
        $before = $product->only(['name', 'detail', 'cover_image']);

        // Handle cover image upload
        if ($coverImage = $this->handleImageUpload()) {
            // Delete old image if exists
            $this->deleteImageFile($product->cover_image);
            $validated['cover_image'] = $coverImage;
        }

        $product->update($validated);
        AuditLogger::log('product.updated', $product, 'Produk diperbarui.', [
            'before' => $before,
            'after' => $validated,
        ]);

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
        $product = Product::find($this->dataId);

        if ($product) {
            // Delete cover image file if exists
            $this->deleteImageFile($product->cover_image);

            AuditLogger::log('product.deleted', $product, 'Produk dihapus.', [
                'attributes' => $product->only(['name', 'detail', 'cover_image']),
            ]);

            $product->delete();
        }

        $this->toast('success', 'Data berhasil dihapus.');
        $this->closeModal();
        $this->resetInput();
    }
}
