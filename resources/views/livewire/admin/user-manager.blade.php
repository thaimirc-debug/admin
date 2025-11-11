<div>
    @if (session()->has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div>
        <div class="row">
            <div class="col-6">
                <button wire:click="create" class="mb-3 btn btn-blue">เพิ่มข้อมูลพนักงาน <i
                        class="fa-solid fa-indent"></i></button>
            </div>
            <div class="col-6">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
                    class="form-control rounded border px-4 py-2" />
            </div>
            @auth
                @php 
                $ibranch_id = Auth::user()->branch_id;
                @endphp
                @if(Auth::user()->level >= 99)
                <!-- Admin version -->
                <div class="col-md-3">
                    <select wire:model.change="filterBranch" class="form-select mb-3">
                        <option value="">-- แสดงทุกสาขา --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            @endauth
        </div>

        <div class="row">
            @forelse ($users ?? [] as $user)
            <div class="col-lg-4 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        @php
                        if ($user->level==1) {
                        $userCall = 'Staff';
                        }
                        elseif($user->level==10) {
                        $userCall = 'Admin';
                        }
                        elseif($user->level==99) {
                        $userCall = 'SAdmin';
                        }
                        else {
                        $userCall = 'User';
                        }
                        @endphp
                        <p class="text-success">{{ $user->name }} <strong>:{{ $userCall }}</strong>
                        </p>
                        <p class="text-secondary"><strong>{{ $user->email }}</strong></p>
                        <p></p>
                    </div>
                    <div class="card-footer p-3 text-end">
                        <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-gears"></i> แก้ไขข้อมูล
                        </button>
                        <a wire:click="delete({{ $user->id }})" class="btn btn-sm btn-danger"
                            onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?') || event.stopImmediatePropagation()">
                            <i class="fa-regular fa-trash-can"></i> ลบข้อมูล</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 my-3">
                <p class="text-center bg-white p-3 text-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <i class="text-secondary">ไม่มีข้อมูลในฐานข้อมูล!</i>
                </p>
            </div>
            @endforelse
            <div class="py-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>


    <!-- Modal -->
    @if ($isOpen)
    <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title"><i class="fa-solid fa-gears"></i> {{ ($isEditMode ? 'แก้ไขข้อมูล' :
                        'เพิ่มข้อมูลผู้ใช้งาน') }}</h3>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label><strong>ชื่อ</strong></label>
                            <input type="text" class="form-control" wire:model="name" id="name" placeholder="กรอกชื่อ"
                                autocomplete="new-name">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>อีเมล</strong></label>
                            <input type="email" class="form-control" wire:model="email" id="email"
                                placeholder="กรอกอีเมล" autocomplete="email">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>รหัสผ่าน</strong></label>
                            <input type="password" class="form-control" wire:model="password" placeholder="รหัสผ่านใหม่"
                                autocomplete="new-password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                            @if ($isEditMode)
                            <small class="text-muted">* ปล่อยว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label><strong>ระดับ</strong></label>
                            <select class="form-select" wire:model="level" aria-label="Default select">
                                <option value="0" selected>User</option>
                                <option value="1">Moderator</option>
                                @if(auth()->user()->level >= 10)
                                <option value="10">Admin</option>
                                @endif
                                @if(auth()->user()->level == 100)
                                <option value="99">SAdmin</option>
                                @endif
                            </select>
                            @error('level') <span class="text-danger italic">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <select wire:model="branch_id" class="form-select mb-2">
                                <option value="">-- เลือกสาขา --</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-end pb-2">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary"><i
                                    class="fa-solid fa-reply"></i> ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-download"></i> {{ $isEditMode ?
                                'อัพเดทข้อมูล' :
                                'บันทึกข้อมูล'}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('title')
admin.users
@endpush

@push('description')
ระบบจัดการพนักงาน
@endpush

@push('sidebar')
@include('livewire.layouts.sidebar')
@endpush