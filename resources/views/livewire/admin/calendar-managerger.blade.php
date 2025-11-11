<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button wire:click="goToPreviousMonth" class="btn btn-light text-secondary"><i class="fa-solid fa-arrow-left"></i></button>
        <h2>{{ $monthName }}</h2>
        <button wire:click="goToNextMonth" class="btn btn-light text-secondary"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
    <table class="table table-bordered shadow" style="border-color: rgb(125, 61, 185)">
        <thead>
            <tr class="text-center">
                <th class="table-light" style="border-color: rgb(182, 104, 255)">จ</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">อ</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">พ</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">พฤ</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">ศ</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">ส</th>
                <th class="table-light" style="border-color: rgb(182, 104, 255)">อา</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $week)
            <tr class="text-center">
                @foreach ($week as $day)
                <td class="{{ isset($appointments[$day]) ? 'bg-yellow text-dark cursor-pointer' : '' }}"
                    @if(isset($appointments[$day])) wire:click="showAppointmentModal({{ $day }})" @endif>
                    @if ($day)
                    {{ $day }}
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($isOpen)
    <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-bg-white shadow">
                <div class="modal-header">
                    <h3 class="modal-title" id="appointmentModalLabel">
                        <strong><i class="bi bi-calendar3"></i> การนัดหมาย</strong>
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    @if (!empty($selectedAppointments))
                    <p><strong>นัดหมายทั้งหมด</strong> {{ $selectedDay }}/{{ $currentDate->format('m') }}/{{
                        $currentDate->format('Y')+543 }}</p>
                    @foreach ($selectedAppointments as $appointment)
                    <div class="mb-3 p-2 bg-light border rounded">
                        <div class="row mt-2">
                            <div class="col-5 mb-2">
                                <p>
                                    @php
                                    $appointmentTime = \Carbon\Carbon::parse($appointment['appointment_at']);
                                    @endphp
                                    @if ($appointmentTime->hour === 0 && $appointmentTime->minute === 0)
                                    <span class="py-1 px-2 bg-info text-white rounded shadow">ยังไม่นัดเวลา</span>
                                    @else
                                    <span class="py-1 px-2 bg-info text-white rounded shadow">{{
                                        $appointmentTime->format('H:i') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-7 text-end">
                                @if ($appointment['is_done'] == true)
                                <span class="py-1 px-2 bg-success text-white rounded shadow"><i
                                        class="fa-solid fa-circle-check"></i> เสร็จเรียบร้อย</span>
                                @else
                                <span class="py-1 px-2 bg-warning text-white rounded shadow"><i
                                        class="fa-solid fa-clock"></i> รอดำเนินการ..</span>
                                @endif
                            </div>
                        </div>
                        <p><strong>ลูกค้า</strong> <span class="text-primary">{{ $appointment['customer']['name'] ??
                                'ไม่ระบุ' }}</span></p>
                        <p><strong><i class="bi bi-geo-alt-fill text-danger"></i> ที่อยู่</strong> {{
                            $appointment['customer']['address'] ?? 'ไม่ระบุ' }}</p>
                        <p><strong><i class="bi bi-phone-fill text-danger"></i> โทร</strong>
                            <span class="text-primary">
                                @foreach ($appointment['customer']['phones'] ?? [] as $p)
                                @php
                                $telNumber = str_replace(['-', ' '], '', $p);
                                @endphp
                                <a href="tel:{{ $telNumber }}" class="small">{{($loop->iteration > 1) ? ',':''}} {{ $p
                                    }}</a>
                                @endforeach
                            </span>
                        </p>
                        <p class="mt-2"><strong>บริการ</strong> {{ $appointment['service'] }}</p>
                        <p><strong>รายละเอียด</strong> {{ $appointment['description'] }}</p>
                        <button class="btn btn-sm btn-outline-blue">
                            แก้ไขเพิ่มเติม
                        </button>
                    </div>
                    <div class="row">
                        <div>
                            @if(count($appointment['images']) ?? [])
                            @php
                            $ico = ($expandedCustomerId != $appointment['customer']['id']) ? '<i
                                class="fa-solid fa-chevron-down"></i>' : '<i class="fa-solid fa-chevron-up"></i>';
                            @endphp
                            <div class="text-end">
                                <button class="btn btn-outline-warning shadow btn-sm"
                                    wire:click="$set('expandedCustomerId', {{ $expandedCustomerId === $appointment['customer']['id'] ? 'null' : $appointment['customer']['id'] }})">
                                    {{-- กำหนด $customer ให้เป็น $appointment['customer'] --}}
                                    {!! $ico ?? '' !!} {{ $expandedCustomerId === $appointment['customer']['id']
                                    ? 'ดูรูปภาพ' : 'ดูรูปภาพ' }} <i class="fa-solid fa-image"></i> {{
                                    count($appointment['images']) }}
                                </button>
                            </div>
                            @endif

                            @if ($expandedCustomerId === $appointment['customer']['id'])
                            <div class="mt-3 mb-3">
                                @if (!empty($appointment['images']))
                                <div class="row row-cols-4 g-2">
                                    @foreach ($appointment['images'] as $image)
                                    <div class="image-crop">
                                        <a href="{{ asset('apps/' . $image['path']) }}" target="_blank">
                                            <img src="{{ asset('apps/' . $image['path']) }}"
                                                class="img-fluid rounded shadow img-thumbnail" alt="รูปภาพ">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    {{-- @else
                    <p>ไม่มีข้อมูลนัดหมายในวันที่เลือก</p> --}}
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeModal">ปิดหน้าต่าง</button>
                    {{-- <button type="button" class="btn btn-blue" data-bs-dismiss="modal"
                        wire:click="{{route('admin',$appointment['customer']['id'])}}">แก้ไขข้อมูล {{ $appointment['customer']['id'] }}</button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('navbar')
@include('livewire.layouts.index-nav')
@endpush

@push('title')
admin.calendar
@endpush

@push('description')
ระบบจัดการนัดหมาย
@endpush

@push('sidebar')
@include('livewire.layouts.sidebar')
@endpush
