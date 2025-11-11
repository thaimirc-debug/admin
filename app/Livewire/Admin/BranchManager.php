<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;
use Auth;

class BranchManager extends Component
{
    use WithPagination;

    public $name, $branchId;
    public $isOpen = false, $isEditMode = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:2',
    ];

    public function mount() {
        if (Auth::guest() || Auth::user()->level < 99) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->isEditMode = false;
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branchId = $branch->id;
        $this->name = $branch->name;
        $this->isOpen = true;
        $this->isEditMode = true;
    }

    public function save()
    {
        $this->validate();

        Branch::updateOrCreate(
            ['id' => $this->branchId],
            ['name' => $this->name]
        );

        session()->flash('message', $this->isEditMode ? 'อัพเดทสาขาสำเร็จ' : 'เพิ่มสาขาใหม่สำเร็จ');
        $this->resetForm();
    }

    public function delete($id)
    {
        Branch::findOrFail($id)->delete();
        session()->flash('message', 'ลบสาขาแล้ว');
    }

    public function resetForm()
    {
        $this->reset(['name', 'branchId', 'isOpen', 'isEditMode']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $branches = Branch::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.admin.branch-manager', compact('branches'));
    }
}
