<div>
    <h1 class="mt-0">มีอะไรใหม่วันนี้</h1>
    <div class="row g-3">
        @foreach ($posts as $post)
        <div class="{{($loop->iteration < 3) ? " col-md-6":"col-md-4" }}">
            <div class="card h-100 shadow rounded-4 overflow-hidden">
                @if($post->image)
                <a href="{{ route('posts.show', $post->id) }}" class="hover">
                    <div>
                        <img src="{{ asset('images/'.$post->image) }}" class="card-img-top" alt="...">
                    </div>
                </a>
                @endif
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="card-title">{{$post->title}}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto"> <small class="text-muted">
                            <i class="bi bi-eye"></i> {{ number_format($post->views) }} views
                        </small>
                        <a href="{{ route('posts.show', $post->id) }}"
                            class="btn btn-blue btn-sm rounded-pill px-3">อ่านเพิ่มเติม</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- เพิ่มการ์ดอื่นๆ คล้ายกันได้ -->
        <div class="text-center my-4">
            @if ($posts->hasMorePages())
            <button wire:click="loadMore" wire:loading.attr="disabled"
                class="btn btn-outline-blue shadow rounded-4 position-relative">
                <!-- ข้อความปุ่ม -->
                <span wire:loading wire:target="loadMore" class="spinner-border spinner-border-sm ms-2 text-primary"
                    role="status" aria-hidden="true"></span>
                <span>โหลดเพิ่มเติม</span>
            </button>
            @endif
        </div>
    </div>


    <div class="row g-3">
        <!-- ปัญหา/สอบถาม -->
        <div class="col-md-6">
            <div class="card card-custom border-top-info">
                <div class="card-header bg-light card-header-custom">
                    💬 ปัญหา/การสอบถาม ที่เพิ่งเปิดไปเมื่อเร็วๆ นี้
                </div>
                <div class="card-body small-text">
                    <span class="d-block"><strong>#PFT-900063</strong> -
                        ขอเอกสารยืนยันตัวตนตอนเปิดใช้งานบริการ</span>
                    <span class="text-muted">แก้ไขครั้งล่าสุด: Thursday May 29th, 2025 (14:16)</span><br>
                    <span class="badge bg-success badge-status mt-1">เปิด</span><br>
                    <a href="#" class="btn btn-sm btn-outline-info mt-2">เปิดแจ้งปัญหา/สอบถามใหม่</a>
                </div>
            </div>
        </div>

        <!-- จดทะเบียนโดเมนใหม่ -->
        <div class="col-md-6">
            <div class="card card-custom border-top-violet shadow">
                <div class="card-header bg-light card-header-custom">
                    🌐 จดทะเบียนโดเมนใหม่
                </div>
                <div class="card-body">
                    <div class="input-group input-group-custom">
                        <input type="text" class="form-control" placeholder="พิมพ์ชื่อโดเมน">
                        <button class="btn btn-success">สมัครใช้งาน</button>
                        <button class="btn btn-outline-secondary">โอนย้าย</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('navbar')
@include('livewire.layouts.index-navbar')
@endpush


@push('sidebar')
@include('livewire.layouts.index-sidebar')
@endpush

@push('ads')
@include('livewire.layouts.ads')
@endpush

@push('title')
PS HOME CATE THAILAND รับกำจัดปลวก 088-980-9969 ทั้งระบบวางเหยื่อ และระบบน้ำยา ทีมงานมืออาชีพ
@endpush

@push('description')
PS HOME CATE THAILAND รับกำจัดปลวก 088-980-9969 ทั้งระบบวางเหยื่อ และระบบน้ำยา ปลอดภัย รับรองผล ทีมงานมืออาชีพ
@endpush

@push('keywords')
รับกำจัดปลวก, ระบบวางเหยื่อปลวก, น้ำยาฆ่าปลวก, น้ำยาป้องกันปลวก
@endpush

