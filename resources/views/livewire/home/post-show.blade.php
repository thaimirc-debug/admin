<div>
    @if ($post->image)
    <div class="col">
        <img src="{{ asset('images/' . $post->image) }}" width="100%" class="img-fluid mb-4" alt="{{ $post->title }}">
    </div>
    @endif

    <h1 class="mb-0">{{ $post->title }}</h1>
    @foreach($categories ?? [] as $category)
        @if($category->id == $post->category_id)
        <p class="mb-2 small text-end"> 
            <span class="text-muted bg-violet px-2 py-1 rounded-3">
            หมวด{{ $category->name }}</span>
        </p>
        @endif
    @endforeach
    <div class="row">
        <div class="col-6">
            <small class="text-muted">
                <i class="bi bi-eye"></i> {{ number_format($post->views) }} views
            </small>
        </div>
        <div class="col-6">
            <p class="text-muted small text-end"><i>เขึยนมื่อ.. {{ thai_date($post->created_at->format('F d, Y')) }}</i>
            </p>
        </div>
    </div>

    <div class="mt-4">
        {!! $post->content !!}
    </div>

    <div class="my-4 text-end">
        <a href="{{ route('index') }}" class="btn btn-outline-secondary" title="Pshome Index">← Back to index</a>
    </div>
</div>

@push('navbar')
@include('livewire.layouts.index-navbar')
@endpush

@push('title')
{{ $post->title }}
@endpush

@push('description')
{{ strip_tags($post->description) }}
@endpush

@push('keywords')
{{ $post->keywords }}
@endpush

@push('sidebar')
@include('livewire.layouts.index-sidebar')
@endpush