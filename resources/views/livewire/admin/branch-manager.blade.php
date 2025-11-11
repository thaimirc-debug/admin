<div>
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6 mb-3">
            <button wire:click="create" class="btn btn-blue"><i class="fa fa-plus"></i> เพิ่มสาขา</button>
        </div>
        <div class="col-md-6">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="ค้นหาสาขา...">
        </div>
    </div>

    <div class="row">
        @forelse ($branches as $branch)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    สาขา <strong class="text-secondary">{{ $branch->name }}</strong>
                </div>
                <div class="card-footer text-end">
                    <button wire:click="edit({{ $branch->id }})" class="btn btn-sm btn-secondary">
                        <i class="fa fa-edit"></i> แก้ไข
                    </button>
                    <button wire:click="delete({{ $branch->id }})" class="btn btn-sm btn-danger"
                        onclick="confirm('ลบสาขานี้?') || event.stopImmediatePropagation()">
                        <i class="fa fa-trash"></i> ลบ
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">
            ไม่มีข้อมูลสาขา
        </div>
        @endforelse
    </div>

    {{ $branches->links() }}

    @if ($isOpen)
    <div class="modal show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditMode ? 'แก้ไขสาขา' : 'เพิ่มสาขา' }}</h5>
                    <button type="button" wire:click="resetForm" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>ชื่อสาขา</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="text-end">
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
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
admin.branchs
@endpush

@push('description')
    ระบบจัดการสาขา
@endpush

@push('sidebar')
    @include('livewire.layouts.sidebar')
@endpush
