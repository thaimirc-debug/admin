<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public $isOpen   = false, $cat, $name, $description, $pin, $position, $cat_Id;
    protected $rules = [
        'name'        => 'required|string',
        'description' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateCategoryOrder($ids)
    {
        foreach ($ids as $index => $id) {
            Category::where('id', $id)->update(['position' => $index + 1]);
        }
        $this->categories = Category::orderBy('position')->get();
    }

    public function create()
    {
        $this->reset();
        $this->openModal();
    }

    public function edit($id)
    {
        $c = Category::findOrFail($id);
        $this->cat_Id       = $c->id; // เพิ่มบรรทัดนี้เพื่อกำหนด $catId
        $this->name         = $c->name;
        $this->description  = $c->description;

        $this->pin     = $c->pin;
        $this->openModal();
    }

    public function save()
    {
        $this->validate();
        $catData = [
            'name'        => $this->name,
            'slug' => str_slug($this->name),
            'description' => $this->description,
            'position'    => $this->position ?: 0,  // ตั้งค่า default เป็น 0 หากไม่ได้กำหนด
            'pin'     => $this->pin ?: 0,
        ];
        if ($this->cat_Id) {
            Category::find($this->cat_Id)->update($catData);
        } else {
            Category::create($catData);
        }
        session()->flash('message', $this->cat_Id ? 'Category created successfully.' : 'Category updated successfully.');
        $this->reset(); // รีเซ็ตค่าหลังจากบันทึกข้อมูล
        $this->closeModal();
        // return redirect()->route('category.show', $this->cat);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->cats = Category::latest()->get();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function mount()
    {
        // $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->cats = Category::whereNull('s_id')
            ->with(['children' => fn($q) => $q->orderBy('position')])
            ->orderBy('position')
            ->get();
    }

    public string $search = '';

    public function render()
    {
        $c = \App\Models\Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('position')
            ->paginate(10);

        return view('livewire.admin.category-manager',[
                'cats' => $c,
        ]);
    }
}


