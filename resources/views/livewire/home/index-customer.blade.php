<div>
    <div class="row mb-3">
        <div class="col-6">
            <h2><i class="fa-solid fa-list"></i> รายละเอียด</h2>
        </div>
        <div class="col-6 text-end">
            <a href="{{route('customers.index')}}" class="btn btn-blue">
                <i class="fa-solid fa-computer"></i> รายชื่อลูกค้า
            </a>
        </div>
    </div>

    <div class="bg-light border p-3 mb-3">
        <h3>ชื่อลูกค้า: {{ $customer->name }}</h3>
        <p><strong>ที่อยู่</strong> {{ $customer->address }}</p>
        <p><strong>แพ็คเกจ</strong> 
            @if($customer->packet == 1)
            2ครั้ง/1ปี
            @elseif($customer->packet == 2)
            4ครั้ง/1ปี
            @else
            วางเหยื่อ 2 ระบบ
            @endif
        </p>
        <p class="mb-2"><strong>ราคา</strong> {{ $customer->price+0 }} บาท</p>
        <p class="mb-2">
            @foreach ($customer->phones ?? [] as $p)
            <span class="small">
                <a href="tel:{{ $p }}" class="btn btn-outline-pink btn-sm shadow">
                    <i class="fa-solid fa-mobile-screen-button"></i> {{ $p }}
                </a>
            </span>
            @endforeach
        </p>
        <div class="col text-end">
            <button class="btn btn-sm btn-outline-secondary shadow" onclick="copyCustomerText()">
                <i class="fa-solid fa-copy"></i> คัดลอกข้อมูลลูกค้า
            </button>
            <a href="{{ url('customer/' . $customer->id . '/plan') }}" target="_blank" class="btn btn-sm btn-pink shadow"><i class="fa-solid fa-address-card"></i> ดูใบงานลูกค้า</a>
        </div>
    </div>
    <div class="mb-3">
        <p class="fw-bold">
            แสดงนัดหมายทั้งหมด
        </p>
        <ul class="list-group">
            @foreach ($customerApps as $app)
            <li class="list-group-item" style="{{($app->is_done == true ? " background-color:rgba(219, 222,
                197,0.4);":"")}}">
                <div class="py-2">
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="text-white bg-primary p-1 px-2 rounded-3 shadow">
                                <i class="fa-regular fa-calendar"></i>
                                @php
                                $dt = \Carbon\Carbon::parse($app->appointment_at);
                                @endphp
                                {{ $dt->format('d-m') }}-{{ $dt->year + 543 }}
                            </span>
                        </div>
                        <div class="col-6 text-end">
                            @if(\Carbon\Carbon::parse($app->appointment_at)->format('H:i:s') === '00:00:00')

                            <span class="text-danger bg-light p-1 px-2 rounded-3 shadow">
                                ยังไม่ได้นัดเวลา</span>
                            @else
                            <span class="text-white bg-success p-1 px-2 rounded-3 shadow">
                                <i class="fa-regular fa-clock"></i>
                                เวลา {{
                                \Carbon\Carbon::parse($app->appointment_at)->format('H:i') }} น.</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <p>
                            <strong>การทำงาน</strong>
                            {{ $app->service }}
                        </p>
                        <div>
                            <strong>อธิบายงาน</strong>
                            <p class="bg-light p-2 rounded-4">
                                {!! nl2br(e($app->description)) !!}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button wire:click="editApps({{$customer->id}},{{$app->id}})"
                                class="btn btn-outline-pink btn-sm shadow">
                                แก้ไขเพิ่มเติม
                            </button>
                        </div>
                        <div class="col-6">
                            <p class="text-end">
                                <span class="text-dark {{ ($app->is_done == true) ? " bg-success":"bg-warning" }} p-1
                                    px-2 rounded-3 shadow">
                                    @if($app->is_done == true)
                                    <span class="text-white"><i class="fa-solid fa-thumbs-up"></i> ดำเนินการแล้ว</span>
                                    @else
                                    รอดำเนินการ...
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    @if ($app->images && $app->images->count() > 0)
                        <div class="row g-2 mt-3">
                            @foreach ($app->images as $index => $img)
                                @if ($img && $img->path)
                                    <div class="col-3">
                                        <a href="{{ asset('apps/' . $img->path) }}" target="_blank" rel="noopener noreferrer">
                                            <p class="image-crop">
                                                <img src="{{ asset('apps/' . $img->path) }}" class="img-thumbnail" alt="Appointment Image {{ $index + 1 }}" width="100%">
                                            </p>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    {{-- @if ($app->images)
                    <div class="row g-2 mt-3">
                        @foreach ($app->images as $index => $img)                                                        
                        @if ($img)
                        <div class="col-3">
                            @if (is_string($img->path))
                            <a href="{{ asset('apps/' . $img->path) }}" target="_blank">
                                <p class="image-crop">
                                    <img src="{{ asset('apps/' . $img->path) }}" class="img-thumbnail" width="100%">
                                </p>
                            </a>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif --}}
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Modal ย่อย: เพิ่มนัดหมาย -->
    @if ($isAppOpen)
    <div class="modal show d-block" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-light">
                    <h3 class="modal-title"><i class="fa-solid fa-calendar-days"></i> {{ $appointmentId ? 'แก้ไขนัดหมาย'
                        : 'เพิ่มนัดหมายใหม่' }}</h3>
                    <button type="button" class="btn-close" wire:click="closeAppModal"></button>
                </div>
                <form wire:submit.prevent="saveAppointment">

                    <div class="modal-body">
                        <div class="mb-2">
                            <label><strong>วันที่และเวลา</strong></label>
                            <input type="datetime-local" wire:model="appointment_at" class="form-control">
                            @error('appointment_at') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2">
                            <label><strong>บริการ</strong></label>
                            <input type="text" wire:model="service" class="form-control">
                            @error('service') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>ลักษณะงาน</strong></label>
                            <textarea wire:model="description" class="form-control"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode"
                                    wire:model="is_done" {{ $is_done ? 'checked' : '' }}>
                                <label class="form-check-label" for="mySwitch">ทำเรียบร้อยแล้ว</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            
                            <label class="form-label mb-0"><strong>อัปโหลดรูป (หลายรูปได้)</strong></label>
                            <input type="file" class="form-control" wire:model="images" multiple>
                            @error('images.*') <small class="text-danger">{{ $message }}</small> @enderror
                            
                            @if ($images)
                            <div class="row mt-3">
                                @foreach ($images as $index => $image)
                                <div class="col-6 mb-2 position-relative">
                                    @if (is_string($image))
                                    <a href="{{ asset('apps/' . $image) }}" target="_blank">
                                        <p class="image-crop">
                                            <img src="{{ asset('apps/' . $image) }}" class="img-thumbnail">
                                        </p>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        wire:click="deleteImage('{{ $image }}')" title="ลบภาพนี้">
                                        &times;
                                    </button>
                                    @elseif (is_object($image))
                                    <p class="image-crop">
                                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail">
                                    </p>
                                    <button type="button" class="btn btn-pink btn-sm position-absolute top-0 end-0"
                                        wire:click="removeTempImage({{ $index }})">
                                        &times;
                                    </button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            ---
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeAppModal"><i
                                class="fa-solid fa-reply"></i> ยกเลิก</button>
                        <button type="submit" class="btn btn-blue" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveAppointment">
                                <i class="fa-solid fa-download"></i>
                                {{ $appointmentId ? 'อัพเดทนัดหมาย' : 'บันทึกนัดหมาย'}}
                            </span>
                            <span wire:loading wire:target="saveAppointment">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                กำลังประมวลผล...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- backdrop ซ้อน -->
    <div class="modal-backdrop fade show" style="z-index: 1059;"></div>
    @endif
    <div class="text-end">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">ย้อนกลับหน้าที่แล้ว</a>
    </div>
</div>

@push('title')
PS HOME CATE THAILAND รับกำจัดปลวก 088-980-9969
@endpush

@push('description')
PS HOME CATE THAILAND รับกำจัดปลวก ปรึกษาเรา 088-980-9969 ทั้งระบบวางเหยื่อ และระบบน้ำยา ปลอดภัย รับรองผล ทีมงานมืออาชีพ
@endpush

@push('keywords')
รับกำจัดปลวก, ระบบวางเหยื่อปลวก, น้ำยาฆ่าปลวก, น้ำยาป้องกันปลวก
@endpush

@push('navbar')
@include('livewire.layouts.index-navbar')
@endpush

@push('sidebar')
{{-- @include('livewire.layouts.index-sidebar') --}}
@endpush

@push('ads')
{{-- @include('livewire.layouts.ads') --}}
@endpush

@push('scripts')

<script>
    function copyCustomerText() {
        const name = @json($customer->name);
        const address = @json($customer->address);
       const packet = @json($customer->packet == 1 ? '2ครั้ง/1ปี' : ($customer->packet == 2 ? '4ครั้ง/1ปี' : 'วางเหยื่อ 2 ระบบ'));
        const price = @json($customer->price+0) + ' บาท';

        const phones = @json($customer->phones ?? []);
        
        const phoneText = phones.map(p => `📞 ${p}`).join('\n');
        const text = `ชื่อลูกค้า: ${name}\nที่อยู่: ${address}\nแพ็คเกจ: ${packet}\nราคา: ${price}\n${phoneText}`;

        navigator.clipboard.writeText(text)
            .then(() => {
                alert("คัดลอกข้อมูลลูกค้าเรียบร้อยแล้ว");
            })
            .catch(err => {
                console.error("ไม่สามารถคัดลอกได้:", err);
                alert("เกิดข้อผิดพลาดในการคัดลอก");
            });
    }
</script>

@endpush