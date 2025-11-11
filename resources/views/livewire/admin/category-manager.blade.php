<div>
    @if (session()->has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <button wire:click="create" class="mb-3 btn btn-blue">เพิ่มหมวดหมู่ใหม่ <i class="fa-solid fa-indent"></i></button>
    <p class="text-end"><i class="fa-solid fa-list-ol"></i> <strong
            class="text-secondary">ลากเพื่อเรียงลำดับหมวดหมู่</strong></p>
    <div x-data x-init="Sortable.create($refs.sortable, {
                    animation: 150,
                    onEnd: function () {
                        const ids = Array.from($refs.sortable.children).map(i => i.dataset.id);
                        $wire.updateCategoryOrder(ids);
                    }
                })">
        <ul class="list-group rounded-0" x-ref="sortable">
            @forelse ($cats as $cat)
            <li class="bg-white list-group-item py-3" style="cursor: pointer;" data-id="{{ $cat->id }}"
                wire:key="cat-{{ $cat->id }}">
                <h5><i class="fa-solid fa-arrows-up-down-left-right"></i>
                    {{sprintf("%02d",$cat->position)}}. {{ $cat->name }} (#{{ sprintf("%02d",$cat->id) }})
                </h5>
                <p class="text-secondary bg-light p-2 py-1 border rounded">{{ $cat->description }}</p>
                <div class="mt-2 text-end">
                    <button wire:click="edit({{$cat->id}})" class="btn btn-sm btn-secondary">
                        <b><i class="fa-solid fa-gears"></i> แก้ไขหมวดหมู่</b></button>
                    <a wire:click="delete({{ $cat->id }})" class="btn btn-sm btn-danger"
                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโพสต์นี้?') || event.stopImmediatePropagation()">
                        <b><i class="fa-regular fa-trash-can"></i> ลบหมวดหมู่</b>
                    </a>
                </div>
            </li>
            @empty
            <div class="col-12 my-3">
                <p class="text-center bg-white p-3 text-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <i class="text-secondary">ไม่มีข้อมูลในฐานข้อมูล!</i>
                </p>
            </div>
            @endforelse
        </ul>
    </div>

    @if ($isOpen)
    <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title"><i class="fa-solid fa-computer"></i> {{ ($cat_Id ? 'แก้ไขหมวดหมู่' : 'สร้างหมวดหมู่') }}</h3>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="form-group pb-3">
                            <label for="name"><b>ชื่อหมวดหมู่</b></label>
                            <input type="text" wire:model="name" class="form-control" id="name">
                            @error('name') <i class="small text-danger">*** {{ $message }}</i> @enderror
                        </div>
                        <div class="form-group pb-3">
                            <label for="description"><b>คำบรรยาย</b></label>
                            <textarea class="form-control" wire:model="description" id="description"
                                rows="3"></textarea>
                            @error('description') <i class="small text-danger">*** {{ $message }}</i> @enderror
                        </div>
                        <div class="form-check form-switch pb-3">
                            {{-- {{ dd($pin) }} --}}
                            <label class="form-check-label" for="pin"><b>ปักหมุดแขวน</b></label>
                            <input class="form-check-input" type="checkbox" id="pin" wire:model="pin" {{($pin
                                ? ' checked' : '' )}}>
                        </div>
                        <div class="form-group text-end pb-3">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary"><i
                                    class="fa-solid fa-reply"></i> ยกเลิก</button>
                            <button type="submit" class="btn btn-blue"><i class="fa-solid fa-download"></i>
                                {{ ($cat_Id ? 'อัพเดทข้อมูล' : 'บันทึกข้อมูล') }}
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
admin.category
@endpush

@push('description')
    ระบบจัดการหมวดหมู่
@endpush

@push('sidebar')
    @include('livewire.layouts.sidebar')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush