<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserManager extends Component
{
    use WithPagination;

    // public $editingUserId;
    public $search     = '', $branch_id, $userId, $name, $email, $password, $level;
    public $isEditMode = false, $isOpen = false;
    public $branches;
    public $filterBranch = null;

    public function mount()
{
    // ตรวจสอบการล็อกอิน
    if (Auth::guest()) {
        return redirect()->route('login');
    }

    // ตรวจสอบสิทธิ์ผู้ใช้
    if (Auth::user()->level < 10) {
        abort(403, 'คุณไม่มีสิทธิ์ใช้งานพื้นที่นี้');
    }

    // โหลดข้อมูลสาขาตามระดับผู้ใช้
    if (Auth::user()->level == 10) {
        // สำหรับผู้ใช้ระดับ 10 แสดงเฉพาะสาขาของตัวเอง
        $this->branches = Branch::where('id', Auth::user()->branch_id)->get();
    } else {
        // สำหรับแอดมิน (ระดับ > 10) แสดงทุกสาขา
        $this->branches = Branch::all();
    }

    // ตั้งค่าการกรองเริ่มต้น
    $this->filterBranch = Auth::user()->level == 10 
        ? Auth::user()->branch_id 
        : null;
}

    // public function mount()
    // {
    //     if (Auth::guest() || Auth::user()->level < 10) {
    //         // abort(403, 'Unauthorized action.');
    //         abort(403, 'คุณไม่มีสิทธิ์ใช้งานพื้นที่นี้');
    //     }
    //     if (Auth::user()->level == 10) {
    //         $this->branches = Branch::where('branch_id',Auth::user()->branch_id);
    //     }
    //     else {
    //         $this->branches = Branch::all();
    //     }
    // }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    protected function rules()
    {
        $emailRule = 'required|email|unique:users,email';
        if ($this->isEditMode && $this->userId) {
            $emailRule = 'required|email|unique:users,email,' . $this->userId;
        }
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'name'      => 'required|string|max:255',
            'email'     => $emailRule,
            'password'  => $this->isEditMode ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property, $this->rules());
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
        $this->isEditMode = false;
    }

    public function edit(User $user)
    {
        if (auth()->user()->level <= $user->level && auth()->user()->id != $user->id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขผู้ใช้นี้');
        }
        $this->resetValidation();
        $this->userId     = $user->id;
        $this->name       = $user->name;
        $this->email      = $user->email;
        $this->level      = $user->level;
        $this->branch_id  = $user->branch_id;
        $this->isEditMode = true;
        $this->openModal();
    }

    public function save()
    {
        $data = $this->validate();
        // แปลง branch_id ที่เป็นค่าว่าง ('') ให้เป็น null
        $branchId = $this->branch_id === '' ? null : $this->branch_id;
        if ($this->isEditMode && $this->userId) {
            $user = User::findOrFail($this->userId);
            // ตรวจสอบสิทธิ์
            if (auth()->user()->level <= $user->level && auth()->user()->id != $user->id) {
                abort(403, 'คุณไม่มีสิทธิ์อัปเดตผู้ใช้นี้');
            }

            $user->update([
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => $this->password ? Hash::make($this->password) : $user->password,
                'level'     => $this->level ?? null,
                'branch_id' => $branchId,
            ]);
        } else {
            User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => Hash::make($this->password),
                'level'     => $this->level ?? null,
                'branch_id' => $branchId,
            ]);
        }

        $this->closeModal();
        session()->flash('message', 'บันทึกข้อมูลสำเร็จ!');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'ลบผู้ใช้เรียบร้อยแล้ว!');
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'level', 'branch_id', 'isEditMode']);
        $this->resetValidation();
    }

    public function render()
    {
        $query = User::where('level', '<', 100)
            ->when($this->filterBranch, fn($q) => $q->where('branch_id', $this->filterBranch))
            ->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
        });
        return view('livewire.admin.user-manager', 
        ['users' => $query->latest()->paginate(10),]
    );
    }
}
