<div>
    <div class="row">
        <div class="col-5">
            <select wire:model.change="filterBranch" class="form-select mb-3">
                <option value="">- แสดงทุกสาขา -</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-7">
            <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="Search users..."
                class="form-control form-control-sm rounded border px-4 py-2" />
        </div>
    </div>
    <div class="bg-light border mb-3">
        <ul class="list-group list-group-flush">
            @forelse ($customers ?? [] as $customer)
            <li class="list-group-item">
                <div class="py-2">
                    <p class="mb-2">
                        <strong>ชื่อลูกค้า</strong> 
                        <a href="{{ route('customer.view', ['customer' => $customer->id]) }}"
                        class="btn btn-outline-primary shadow">
                        <strong>{{ $customer->name }}</strong> <i class="fa-solid fa-eye"></i>
                        </a>
                    </p>
                    <p><strong>เริ่มงาน</strong> 
                        {{ \Carbon\Carbon::parse($customer->start_date)->format('d-m')}}-{{ \Carbon\Carbon::parse($customer->start_date)->format('y')+43 }}
                    </p>
                    <p>
                        <strong>ที่อยู่</strong> 
                        {!! nl2br(e($customer->address)) !!}
                        {{-- {{ $customer->address }} --}}
                    </p>
                    <strong>แพ็คเก็ต</strong>
                    @if($customer->packet == '1')
                    2ครั้ง/1ปี
                    @elseif($customer->packet == '2')
                    4ครั้ง/1ปี
                    @else
                    วางเหยื่อ 2 ระบบ
                    @endif
                    <p class="mb-2">
                        <strong>ราคา</strong> {{ $customer->price }} บาท
                    </p>
                    <p>
                        @foreach ($customer->phones ?? [] as $p)
                        <span class="small">
                            @if($p)
                            <a href="tel:{{ $p }}" class="btn btn-outline-blue btn-sm shadow"> 
                                <i class="fa-solid fa-phone"></i> {{ $p }}
                            </a>
                            @endif
                        </span>
                        @endforeach
                    </p>
                </div>
            </li>
            @empty
            <p>ไม่พบข้อมูลที่ต้องการ</p>
            @endforelse
    </div>
</div>

@push('navbar')
@include('livewire.layouts.index-navbar')
@endpush