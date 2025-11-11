<div class="sidebar" style="width: 100%; position: sticky; top: 20px; align-self: start;">
    {{-- <div class="col trafic rounded-4 p-2 shadow">
        <div class="rounded-3 f-ibm p-1" style="border:2px solid white;">
            <a href="{{route('index')}}" class="active">หน้าแรก</a>
            <a href="#">สาระน่ารู้</a>
            <a href="#">เด็กและสุขภาพ</a>
            <a href="{{route('customers.index')}}">จัดการลูกค้า</a>
            <a href="{{route('calendar.index')}}">ตารางนัดหมาย</a>
            <a href="#">รายงาน</a>
            <a href="#">ตั้งค่า</a>
        </div>
    </div> --}}


    <h1>ประเด็นร้อน</h1>
    <div class="row g-4">
        @foreach ($fposts ?? [] as $fpost)
            @if($fpost->id != $post->id)
            <div class="col-12">
                <div class="card h-100 border-0 bg-light overflow-hidden">
                    <a href="{{ route('posts.show', $fpost->id) }}" class="hover">
                        <div>
                            <img src="{{ asset('images/'.$fpost->image) }}" class="card-img-top" alt="...">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column justify-content-between py-3">
                        <p class="card-title m-0">
                            <a href="{{ route('posts.show', $fpost->id) }}" class="text-dark">{{$fpost->title}}</a>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
