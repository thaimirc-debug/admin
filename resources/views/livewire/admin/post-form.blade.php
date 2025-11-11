<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <div class="row">
                <div class="col-lg-6">
                    <label for="title" class="form-label"><strong>ชื่อเรื่อง</strong></label>
                    <input type="text" class="form-control" id="title" wire:model="title">
                    @error('title') <span class="text-danger italic">{{ $message }}</span> @enderror
                </div>
                <div class="col-lg-6">
                    <label for="category" class="form-label"><strong>หมวดหมู่</strong></label>
                    <select class="form-select" id="category" wire:model="category_id" aria-label="Default select">
                        <option value="null" selected>เลือกหมวดหมู่ (#pages)</option>
                        @forelse ($cats ?? [] as $item)
                        <option value="{{$item->id}}">{{$item->name}} (#{{$item->id}})</option>
                        @empty
                        @endforelse
                    </select>
                    @error('category') <span class="text-danger italic">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label"><strong>คำบรรยาย</strong><i class="small"> *** ความยาวไม่เกิน 255 อักขระ ช่วยในการทำ seo</i></label>
            <input type="text" class="form-control" id="description" wire:model="description">
            @error('description') <span class="text-danger italic">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3" wire:ignore>
            <label for="editor" class="form-label"><strong>รายละเอียด</strong> </label>
            <textarea id="editor" wire:model.defer="content" class="form-control">
                @if($postId)
                    {{ $post->content }}
                @endif
            </textarea>
            @error('content') <span class="text-danger italic">{{ $message }}</span> @enderror
        </div>
        <div class="row">
            <div class="order-md-4 col-md-6 mb-3">
                <div class="mb-3">
                    <label for="keywords" class="form-label"><strong>คีย์เวิร์ด</strong></label>
                    <input type="text" class="form-control" id="keywords" wire:model="keywords">
                    @error('keywords') <span class="text-danger italic">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="pin" class="form-label"><strong>ปักหมุด</strong></label>
                    <select class="form-select" id="pin" wire:model="pin">
                        <option value="0">ปิด</option>
                        <option value="1">เปิด</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="is_published" class="form-label"><strong>สถานะ</strong></label>
                    <select class="form-select" id="is_published" wire:model="is_published">
                        <option value="0">ยังไม่เผยแพร่</option>
                        <option value="1">เผยแพร่</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="published_at">วันที่เผยแพร่</label>
                    <input type="datetime-local" id="published_at" wire:model="published_at" class="form-control">
                </div>
            </div>
            <div class="order-md-3 col-md-6">
                <label for="image" class="form-label mb-2">
                    <strong>รูปภาพ</strong>
                    <small class="text-secondary"><i>*** auto system
                            <b>resize</b> and <b>crop</b> 640x360 pixel ***</i></small>
                </label>
                <input type="file" class="form-control" id="image" wire:model="image">
                @error('image') <span class="text-danger italic">{{ $message }}</span> @enderror
                @if ($image)
                <div class="mb-3">
                    <p class="mt-3 mb-2"><strong>ภาพตัวอย่าง</strong></p>
                    <div class="image-crop">
                        <img src="{{ $image->temporaryUrl() }}" alt="ภาพตัวอย่าง" class="img-thumbnail shadow" width="100%">
                    </div>
                </div>
                @elseif ($oldImage)
                <div class="mb-3">
                    <p class="mt-3 mb-2"><strong>ภาพปัจจุบัน</strong></p>
                    <div class="image-crop">
                        <img src="{{ asset('images/' . $oldImage) }}" alt="ภาพปัจจุบัน" class="img-thumbnail shadow"
                        width="100%">
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3 text-end">
            <a id="cancel" href="{{ route('admin.posts.index') }}" class="btn btn-secondary"><i class="fa-solid fa-reply"></i>
                ยกเลิก</a>
            <button type="submit" class="btn btn-primary" id="submit"><i class="fa-solid fa-download"></i> {{ $postId ? 'บันทึกการแก้ไข'
                    :
                    'บันทึกบทความ'}}
            </button>
        </div>
    </form>
</div>

@push('title')
admin.posts
@endpush

@push('description')
ระบบจัดการบทความ
@endpush

@push('sidebar')
@include('livewire.layouts.sidebar')
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editor = new Jodit('#editor', {
            toolbarButtonSize: 'middle', // "small" | "tiny" | "xsmall" | "middle" | "large" = 'middle'
            // height: 400,
            uploader: {
                url: '/upload-image',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                format: 'json',
                    isSuccess: function (resp) {
                        return !resp.error;
                    },
                    process: function (resp) {
                        return {
                            files: resp.files || [],
                            path: resp.path,
                            baseurl: resp.baseurl,
                            error: resp.error,
                            msg: resp.msg
                        };
                    },
                    defaultHandlerSuccess: function (data, resp) {
                        var i,
                            field = 'files';
                        if (data[field] && data[field].length) {
                            for (i = 0; i < data[field].length; i += 1) {
                                this.s.insertImage(data.baseurl + data.path + data[field][i]);
                            }
                        }
                    },
                    error: function (e) {
                        this.message.message(e.getMessage(), 'error', 4000);
                    }
            },        
            events: {
                change: content => {
                    @this.set('content', content);
                }
            }
        });
        Livewire.on('refreshEditor', () => {
            editor.value = @this.get('content');
        });
    });
</script>
@endpush
