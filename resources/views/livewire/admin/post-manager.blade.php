<div>
    @if (session()->has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-6">
            <a href="{{route('admin.posts.create')}}" class="mb-3 btn btn-blue">เพิ่มบทความใหม่ <i
                    class="fa-solid fa-indent"></i></a>
        </div>
        <div class="col-6">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search posts..."
               id="seach" class="form-control rounded border px-4 py-2" />
        </div>
    </div>


    <div class="row">
        @forelse ($posts ?? [] as $post)
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-content p-2">
                    @if ($post->image)
                    <a href="{{ route('posts.show', $post) }}" target="_blank" title="{{$post->title}}" class="hover">
                        <div class="shadow">
                            <img src="{{ asset('images/' . $post->image) }}" class="img-thumbnail rounded-0"
                                alt="{{ $post->title }}">
                        </div>
                    </a>
                    @else
                    <p>
                        <img src="{{ asset('noimg.png') }}" class="img-thumbnail rounded-0" alt="{{ $post->title }}">
                    </p>
                    @endif
                    <h1 class="h4 font-bold">
                        <a href="{{ route('posts.show', $post) }}" target="_blank" title="{{$post->title}}"
                            class="cut-text">{{ $post->title }}</a>
                    </h1>
                </div>
                <div class="card-footer p-3 text-end">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-secondary"><i
                            class="fa-solid fa-gears"></i>
                        แก้ไขบทความ</a>
                    <a wire:click="delete({{ $post->id }})" class="btn btn-sm btn-danger"
                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโพสต์นี้?') || event.stopImmediatePropagation()">
                        <b><i class="fa-regular fa-trash-can"></i> ลบบทความ</b>
                    </a>
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
        <div class="mt-4">{{ $posts->links() }}</div>
    </div>
</div>

@push('sidebar')
    @include('livewire.layouts.sidebar')
@endpush

@push('styles')
@endpush

@push('scripts')
@endpush
