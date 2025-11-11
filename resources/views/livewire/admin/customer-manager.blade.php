<div>
    @if (session()->has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-6">
            <button wire:click="create" class="mb-3 btn btn-blue">เพิ่มข้อมูลลูกค้าใหม่ <i
                    class="fa-solid fa-indent"></i></button>
        </div>
        <div class="col-6">
            <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="Search users..."
                class="form-control rounded border px-4 py-2" />
        </div>
        <div class="col-md-3">
            <select wire:model.change="filterBranch" class="form-select mb-3">
                <option value="">-- แสดงทุกสาขา --</option>
                @foreach($branches ?? [] as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        @forelse ($customers ?? [] as $customer)
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <p><strong>ชื่อ <span class="text-primary">{{ $customer->name }} </span></strong></p>
                    <p class="text-dark bg-light p-2 my-2"><strong>ที่อยู่</strong> 
                        {!! nl2br(e($customer->address)) !!}
                        {{-- {{ $customer->address }} --}}
                    </p>
                    <p class="text-dark"><strong>จังหวัด</strong> {{ $customer->province }}</p>
                    <p>
                        <strong>โทรศัพท์</strong>
                        @foreach ($customer->phones ?? [] as $p)
                        <span class="small">{{($loop->iteration > 1) ? ',':''}}{{ $p }}</span>
                        @endforeach
                    </p>
                    <hr>
                    <div>
                        <strong>เริ่มงาน</strong> {{ $customer->start_date->format('d/m/Y') ?
                        thai_date($customer->start_date):'...' }}
                    </div>
                    <div>
                        <strong>ลักษณะงาน</strong>
                        <p class="bg-light p-2 mb-2">
                            {{ $customer->job_description ?? '...' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>ราคา</strong> {{ $customer->price ?? '...' }} บาท
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <a href="{{ url('admin/customer/' . $customer->id . '/print') }}"
                                class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fa-solid fa-print"></i> พิมพ์สัญญา-ตารางงาน
                            </a>
                        </div>
                        @php
                        $ico = ($expandedCustomerId != $customer->id) ? '<i class="fa-solid fa-chevron-down"></i>' : '<i
                            class="fa-solid fa-chevron-up"></i>';
                        @endphp
                        <div class="col-6 text-end">
                            <button class="btn btn-outline-success btn-sm"
                                wire:click="$set('expandedCustomerId', {{ $expandedCustomerId === $customer->id ? 'null' : $customer->id }})">
                                {!!$ico!!} {{ $expandedCustomerId === $customer->id
                                ? 'ย่อข้อมูล' : 'ดูเพิ่มเติม' }}
                            </button>
                        </div>
                    </div>
                </div>

                @if ($expandedCustomerId === $customer->id)
                <hr class="mt-0">
                <div class="px-3">
                    <i class="fa-solid fa-calendar-days"></i> <strong class="text-primary">ตารางนัดหมาย</strong>
                    @if ($customer->appointments->isNotEmpty())
                    <div>
                        @foreach($customer->appointments as $appointment)
                        <div class="pb-3 my-3 border-bottom">
                            <div class="row">
                                <div class="col-12 font-bold text-success">
                                    @if ($appointment->appointment_at)
                                    {{ \Carbon\Carbon::parse($appointment->appointment_at)->format('H:i:s')
                                    != '00:00:00'
                                    ? \Carbon\Carbon::parse($appointment->appointment_at)->format('d/m/Y
                                    H:i').' น.'
                                    : \Carbon\Carbon::parse($appointment->appointment_at)->format('d/m/Y')
                                    }}
                                    @endif
                                    @if ($c = $appointment->images->count())
                                    <span class="text-primary"> <i class="fa-solid fa-image"></i> {{$c}} รูปภาพ</span>
                                    @endif
                                </div>
                            </div>
                            <div class="border bg-light p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <strong><i class="fa-solid fa-star-of-life"></i> {{
                                            $appointment->service
                                            }}</strong>
                                    </div>
                                    <div class="col-6 text-end">

                                        @php
                                        $statusHtml = $appointment->is_done
                                        ? '<span class="shadow bg-success text-white py-1 px-2 rounded small"><i
                                                class="fa-solid fa-circle-check"></i> ทำเรียบร้อยแล้ว</span>'
                                        : '<span class="shadow bg-warning text-white py-1 px-2 rounded small"><i
                                                class="fa-regular fa-clock"></i> รอดำเนินการ.....</span>';
                                        @endphp
                                        <div class="text-end">{!! $statusHtml !!}</div>
                                    </div>
                                </div>
                                <div class="p-2">
                                    {!! nl2br(e($appointment->description)) !!}
                                </div>
                            </div>
                            <div class="text-end mt-2 ">


                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        wire:click="openAppModal({{ $customer->id }},{{$appointment->id}})">แก้ไขข้อมูล</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?') || event.stopImmediatePropagation()"
                                        wire:click="deleteAppModal({{ $customer->id }},{{$appointment->id}})">ลบนัดหมาย</button>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center">
                        <button class="btn btn-outline-warning" wire:click="createAppAuto({{ $customer->id }})">
                            สร้างตารางนัดหมายอัตโนมัติ
                        </button>
                    </div>
                    @endif
                    <div class="pb-3 text-center">
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary shadow"
                                wire:click="openAppModal({{ $customer->id }})">
                                <i class="fa-solid fa-calendar-days"></i> เพิ่มการนัดหมาย
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                <div class="card-footer p-3 text-end">
                    <button wire:click="edit({{ $customer->id }})" class="btn btn-sm btn-secondary">
                        <i class="fa-solid fa-gears"></i> แก้ไขข้อมูล
                    </button>
                    <a wire:click="delete({{ $customer->id }})" class="btn btn-sm btn-danger"
                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?') || event.stopImmediatePropagation()">
                        <i class="fa-regular fa-trash-can"></i> ลบข้อมูลลูกค้า</a>
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
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    @if ($isOpen)
    <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h3 class="modal-title"><i class="fa-solid fa-gears"></i> {{ ($customerId ? 'แก้ไขข้อมูล' :
                        'จัดการข้อมูล') }}</h3>
                    <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label><strong>ชื่อ</strong></label>
                            <input type="text" wire:model.defer="name" placeholder="ชื่อ" class="form-control mb-2">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>ที่อยู่</strong></label>
                            <textarea wire:model.defer="address" placeholder="ที่อยู่"
                                class="form-control mb-2"></textarea>
                            @error('address') <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>จังหวัด</strong></label>
                            <input type="text" wire:model.defer="province" placeholder="จังหวัด"
                                class="form-control mb-2">
                            @error('province') <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label><strong>เบอร์โทร</strong></label>
                            @foreach ($phones as $index => $phone)
                            <div class="d-flex mb-1">
                                <input type="text" wire:model="phones.{{ $index }}" class="form-control me-2">
                                <button type="button" wire:click="removePhone({{ $index }})"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button type="button" wire:click="addPhone" class="btn btn-success btn-sm mb-3"><i
                                    class="fa-solid fa-plus"></i> เพิ่มเบอร์</button>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label for="start_date" class="form-label"><strong>วันที่เริ่มงาน</strong></label>
                                <input type="date" id="start_date" class="form-control" wire:model.defer="start_date">

                                @error('packet') <span class="text-danger italic">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-6">
                                <label for="branch_id" class="mb-2"><strong>สาขา</strong></label>
                                <select wire:model="branch_id" id="branch_id" class="form-select mb-2">
                                    <option value="">-- เลือกสาขา --</option>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="job_description" class="form-label"><strong>ลักษณะงาน</strong></label>
                            <input type="text" id="job_description" class="form-control"
                                wire:model.defer="job_description">
                        </div> --}}
                        <div class="mb-3">
                            <input type="text" class="form-control"
                                list="jobSuggestions"
                                wire:model.defer="job_description">
                            <datalist id="jobSuggestions">
                                @foreach($jobSuggestions as $item)
                                    <option value="{{ $item }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="row mb-4">
                            <div class="col-8">
                                <label><strong>แพ็กเก็ต</strong></label>
                                <select class="form-select" wire:model="packet" aria-label="Default select">
                                    <option value="0" selected>เลือกแพ็กเก็ต</option>
                                    <option value="1">ฉีดพ่นยาน้ำยา 2ครั้ง/1ปี</option>
                                    <option value="2">ฉีดพ่นยาน้ำยา 4ครั้ง/1ปี</option>
                                    <option value="3">วางเหยื่อ 2 ระบบ</option>
                                    <option value="4">ฉีดพ่นยาน้ำยา 4ครั้ง/1ปี เป็นเวลา 3ปี</option>
                                </select>
                                @error('packet') <span class="text-danger italic">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-4">
                                <label for="price" class="form-label mb-0"><strong>ราคา</strong></label>
                                <input type="number" step="10.00" id="price" class="form-control"
                                    wire:model.defer="price">
                            </div>
                        </div>

                        <div class="text-end pb-2">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary"><i
                                    class="fa-solid fa-reply"></i> ยกเลิก</button>
                            <button class="btn btn-blue" type="submit">
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
                                @if ($image)
                                <div class="col-3 mb-2 position-relative">
                                    @if (is_string($image))
                                    <a href="{{ asset('apps/' . $image) }}" target="_blank">
                                        <p class="image-crop">
                                            <img src="{{ asset('apps/' . $image) }}" class="img-thumbnail">
                                        </p>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        wire:click="deleteImage('{{ $image }}')">
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
                                @endif
                                @endforeach
                            </div>
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
