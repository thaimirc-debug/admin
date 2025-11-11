<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>พิมพ์เอกสารลูกค้า - {{ $customer->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background: #eee;
            padding: 10px;
        }

        .page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: auto;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- หน้า 1 : ใบเสนอราคา -->
    <div class="page">
        <div class="row" style="height: 1060px; border:2px solid #666;">
            <div class="col px-0" style="height: 730px;">
                <div class="text-center p-2" style="background-color: lightblue;">
                    <h5>
                        <strong>PS Home care (Thailand) กำจัดปลวก (สาขา{{$customer->branch->name}})</strong>
                    </h5>
                    <p class="m-0">
                        45/8 ถ.ธัญญบุรี-วังน้อย หมู่ 1 ตำบลคลองเจ็ด อำเภอคลองหลวง จังหวัดปทุมธานี (สำนักงานใหญ่)
                    </p>
                    <p class="m-0">
                        เลขกำกับภาษี 0133564000673, 099-3639389 (office)
                    </p>

                </div>
                <div style="border-top:2px solid #666;">
                    <div class="row">
                        <h4 class="mt-3 text-center mb-4"><strong>สัญญาบริการ</strong></h4>
                        <div class="row">
                            <div class="col-6 px-4">
                                <p>
                                    <strong>เลขที่</strong> 
                                    PS-K{{\Carbon\Carbon::parse($customer->start_date)->format('ymd') }}
                                </p>
                            </div>
                            <div class="col-6">
                                <p>
                                    <strong>ลงวันที่</strong>
                                    {{ \Carbon\Carbon::parse($customer->start_date)->format('d-m') . '-' .
                                    (\Carbon\Carbon::parse($customer->start_date)->year + 543) }}<br>
                                    <strong>เวลา:</strong>
                                    {{ (\Carbon\Carbon::parse($firstApp->appointment_at)->format('H:i') == '00:00') ?
                                    '...'
                                    : \Carbon\Carbon::parse($firstApp->appointment_at)->format('H:i') }} น.
                                </p>
                            </div>
                        </div>
                        <div class="row px-4">
                            <div class="col-6">
                                <p>
                                    <strong>ชื่อลูกค้า</strong> {{ $customer->name }}
                                </p>
                            </div>
                            <div class="col-6">
                                <p>
                                    <strong>ที่อยู่</strong> {{ $customer->address }}
                                </p>
                                <p>
                                    <strong>จังหวัด</strong> {{ $customer->province }}<br>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3 px-4">
                            <div class="col-6">
                                <strong>ตกลงให้รับจ้างป้องกัน/กำจัด ปลวก</strong>
                            </div>
                            <div class="col-6">
                                <strong>เบอร์โทร</strong>
                                @foreach ($customer->phones ?? [] as $p)
                                <span class="small">{{($loop->iteration > 1) ? ',':''}}{{ $p }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mb-3 px-4">
                            <div class="col">
                                <strong>สถานที่ทำการจ้าง</strong> {{ $customer->address }} 
                                {{-- จ.{{ $customer->province }} --}}
                            </div>
                        </div>
                        <div class="row px-4">
                            <div class="col-6">
                                รวมค่าจ้างเป็นเงิน
                            </div>
                            <div class="col-6 mb-3">
                                <strong>{{ $customer->price }} บาท</strong>
                            </div>
                        </div>
                        <div class="row px-4">
                            <div class="col-6">
                                <strong>กำหนดชำระเงิน พร้อมให้การบริการ</strong>
                            </div>
                            <div class="col-6 p-2 pe-0 mb-3 rounded bg-light" style="border:2px solid #999;">
                                <div class="row">
                                    <div class="col-3 pe-0 text-end">
                                        <img src="{{asset('qrcode.png')}}" width="55px">
                                    </div>
                                    <div class="col-9" style="vertical-align: middle;">
                                        <p class="mb-2">
                                            SCB-Bank no. 437-027496-9
                                        </p>
                                        <p class="m-0">
                                            AC name: เบญจมาศ เทพหัสชัย
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-4">
                            <p>
                                <strong>กำหนดระยะเวลาบริการ และตรวจสอบ ตั้งแต่วันที่
                                    {{ \Carbon\Carbon::parse($customer->start_date)->format('d-m') . '-' .
                                    (\Carbon\Carbon::parse($customer->start_date)->year + 543) }}
                                    ถึง
                                    @if($customer->packet == 4)
                                    {{ \Carbon\Carbon::parse($customer->start_date)->addYear()->format('d-m') . '-' .
                                    (\Carbon\Carbon::parse($customer->start_date)->addYear()->year + 543) }}
                                    @else {
                                    {{ \Carbon\Carbon::parse($customer->start_date)->addYear(3)->format('d-m') . '-' .
                                    (\Carbon\Carbon::parse($customer->start_date)->addYear(3)->year + 543) }}
                                    }
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="row px-4">
                            <div class="col">
                                @if($customer->packet == 3)
                                <p>
                                    กำจัดปลวกด้วยระบบสถานีเหยื่อภายในตัวบ้าน เข้าตรวจสถานีเหยื่อทุก 15วัน (จนปลวกตายยกรัง) 
                                    ฉีดพ่นน้ำยาป้องกันปลวก รอบๆบ้าน ทุก 3เดือน ตลอดสัญญา 1ปี
                                </p>
                                @elseif($customer->packet == 4)
                                <p>
                                    บริการกำจัดปลวกด้วยน้ำยาไร้กลิ่นโดยการฉีดอัดน้ำยาในครั้งแรกและป้องกันภายในและรอบๆ
                                    ตัวบ้าน
                                </p>
                                <p>
                                    มีบริการตรวจเช็คฉีดพ่นภายในและภายนอกบ้าน ภายในระยะเวลากำหนด
                                    รวม 4ครั้ง/1ปี เป็นเวลา 3ปีตามแผนงานด้านหลัง 
                                </p>
                                @else
                                <p>
                                    บริการกำจัดปลวกด้วยน้ำยาไร้กลิ่นโดยการฉีดอัดน้ำยาในครั้งแรกและป้องกันภายในและรอบๆ
                                    ตัวบ้าน
                                </p>
                                <p>
                                    มีบริการตรวจเช็คฉีดพ่นภายในและภายนอกบ้าน ภายในระยะเวลากำหนด
                                    รวม {{ ($customer->packet == '1' ) ? '2' :'4'}}ครั้ง/1ปี ตามแผนงานด้านหลัง 
                                </p>
                                @endif
                                <p>ข้อสัญญาต่างๆที่ได้พิมพ์ไว้ด้านหลังของสัญญานี้ ถ้ามีให้ถือว่าเป็นส่วนหนึ่งของสัญญาฉบับนี้</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="align-items-end">
                <div class="row" style="margin-top:90px;">
                    <div class="col-6">
                        <p class="text-center mb-4 small">
                            ลงนามแทน Ps Home Care Service
                        </p>
                        <div style="text-align: center;">
                            <p>_____________________________________</p>
                            <p>( Ps Home Care (Thailand) )</p>
                            <p class="small">ตัวบรรจง</p>
                            <p>วันที่ _____/_____/_____</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <p class="text-center mb-4 small">
                            ลงนาม และประทับตราลูกค้า
                        </p>
                        <div style="text-align: center;">
                            <p>_____________________________________</p>
                            <p>({{$customer->name}})</p>
                            <p class="small">ตัวบรรจง</p>
                            <p>วันที่ _____/_____/_____</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- หน้า 2 : ตารางนัดหมาย -->
    <div class="page">
        <div class="row" style="height: 1060px; border:0px solid #666;">
            <div class="col px-0">
                <div class="text-center p-3 mb-3" style="border:1px solid #666; background-color: lightblue;">
                    <h5>
                        <strong>PS Home care (Thailand) กำจัดปลวก (สาขา{{$customer->branch->name}})</strong>
                    </h5>
                    <p class="m-0">
                        45/8 ถ.ธัญญบุรี-วังน้อย หมู่ 1 ตำบลคลองเจ็ด อำเภอคลองหลวง จังหวัดปทุมธานี (สำนักงานใหญ่)
                    </p>
                    <p class="m-0">
                        เลขกำกับภาษี 0133564000673, 099-3639389 (office)
                    </p>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>ชื่อลูกค้า </strong> {{ $customer->name }}
                    </div>
                    <div class="col-6">
                        <strong>โทรศัพท์ </strong>
                        @foreach ($customer->phones ?? [] as $p)
                        <span class="small">{{($loop->iteration > 1) ? ',':''}}{{ $p }}</span>
                        @endforeach
                    </div>
                    <div class="col">
                        <strong>ที่อยู่</strong>
                        {{ $customer->address }}
                    </div>
                </div>
                <div style="border-top:0px solid #666;">
                    <table class="table table-bordered">
                        <thead style="border:1px solid #333 !important; border-top:0;">
                            <tr>
                                <th rowspan="2" class="text-center bg-light" width="15%"
                                    style="vertical-align: middle;">วันที่</th>
                                <th rowspan="2" class="text-center bg-light" style="vertical-align: middle;">
                                    รายละเอียดบริการ</th>
                                <th colspan="2" class="text-center bg-light" width="24%">ลายเซ็นต์</th>
                            </tr>
                            <tr>
                                <th class="text-center bg-light" width="12%">ผู้ให้บริการ</th>
                                <th class="text-center bg-light" width="12%">ลูกค้า</th>
                            </tr>
                        </thead>
                        <tbody style="border:1px solid #333 !important;">
                            @foreach($customer->appointments as $index => $appointment)
                            <tr height="60px">
                                <td class="text-center" style="vertical-align: top;">
                                    @php
                                    if ($loop->iteration > 1) {
                                        $cut_date = explode(' ',thai_date($appointment->appointment_at));
                                        if (strlen($cut_date[0]) == 1) {
                                            $g_date = '&nbsp; &nbsp;' .$cut_date[1].' '.$cut_date[2];
                                        }
                                        else {
                                            $g_date = '&nbsp; &nbsp; &nbsp; ' .$cut_date[1].' '.$cut_date[2];
                                        }
                                    }
                                    else {
                                        $g_date = thai_date($appointment->appointment_at);
                                    }
                                    @endphp
                                    {!! $g_date !!}</td>
                                <td>{{ $appointment->description }}</td>
                                <td class="text-center">
                                    @if($appointment->is_done)
                                    <i class="text-primary">PSHome</i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                    if($appointment->is_done) {
                                        $gn = explode(' ',$customer->name);
                                    }
                                    else {
                                        $gn[0] ='';
                                    }
                                    @endphp
                                    <i class="text-primary">{{ $gn[0] }}</i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ปุ่มพิมพ์ -->
                <div class="text-center my-5 no-print">
                    <button class="btn btn-outline-primary shadow" onclick="window.print()">
                        <i class="fa fa-print"></i> พิมพ์เอกสาร
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- หน้า 3 : บิลเงินสด -->
    <div class="page">
        <div class="row">
            <div class="col-2 pt-4">
                <p class="m-0 mb-3 fw-bold">
                    บิลเงินสด
                </p>
                <p class="m-0 fw-bold">
                    Cash Bill
                </p>
            </div>
            <div class="col-8">
                <h4 class="text-center fw-bold">
                    ห้างหุ้นส่วนจำกัด พีเอสโฮม แคร์ (ไทยแลนด์)
                </h4>
                <p class="text-center m-0 small">
                    45/8 ถ.ธัญญบุรี-วังน้อย หมู่ 1 ตำบลคลองเจ็ด อำเภอคลองหลวง จังหวัดปทุมธานี 12120 (สำนักงานใหญ่) โทร.
                    099-3639389
                </p>
                <p class="text-center m-0 small">
                    เลขประจำตัวผู้เสียภาษี 0133564000673
                </p>
            </div>

            <div class="col-2 text-end pt-4">
                <p class="fw-bold">
                    เล่มที่
                    {{ sprintf("%05d", \Carbon\Carbon::parse($customer->start_date)->format('m')) }}
                </p>
                <p class="fw-bold">
                    เลขที่
                    {{ \Carbon\Carbon::parse($customer->start_date)->format('y') }}{{ sprintf("%03d",
                    \Carbon\Carbon::parse($customer->start_date)->format('d')) }}
                </p>

            </div>
        </div>

        <div class="row  mt-3 p-0">
            <div class="row pe-0">
                <div class="col-6">
                    <p>
                        <strong>นาม</strong> {{ $customer->name }}
                    </p>
                </div>
                <div class="col-6 text-end px-0">
                    <p class="px-0">
                        วันที่ 
                        {{ thai_date($customer->start_date) }}
                    </p>
                </div>
            </div>
            <p class="mt-0">
                <strong>ที่อยู่</strong> {{ $customer->address }}
            </p>
            <table class="table table-bordered">
                <thead style="border:1px solid #333 !important; border-top:0;">
                    <tr>
                        <th rowspan="2" class="text-center bg-light" width="15%" style="vertical-align: middle;">จำนวน
                        </th>
                        <th rowspan="2" class="text-center bg-light" style="vertical-align: middle;">
                            รายการ</th>
                        <th rowspan="2" class="text-center bg-light" style="vertical-align: middle;" width="13%">
                            หน่วยละ</th>
                        <th colspan="2" class="text-center bg-light" width="20%">จำนวนเงิน</th>
                    </tr>
                    <tr>
                        <th class="text-center bg-light" width="13%">บาท</th>
                        <th class="text-center bg-light" width="7%">ส.ต</th>
                    </tr>
                </thead>
                <tbody style="border:1px solid #333 !important;">
                    <tr height="60px">
                        <td class="text-center" style="vertical-align: middle;">1</td>
                        <td>{{ $customer->job_description }}</td>
                        <td class="text-center">
                            {{ $customer->price +0 }}
                        </td>
                        <td class="text-center">
                            {{ $customer->price +0 }}
                        </td>
                        <td class="text-center">
                            -
                        </td>
                    </tr>
                    @for ($i = 0; $i < 6; $i++) <tr height="60px">
                        <td class="text-center" style="vertical-align: middle;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                        @endfor
                        <tr height="60px">
                            <td class="text-center bg-light" style="vertical-align: middle;"></td>
                            <td class="bg-light">
                                <strong> {{ baht_text($customer->price) }}</strong> (ตัวอักษร)
                            </td>
                            <td class="text-center bg-light">รวมเป็นเงิน</td>
                            <td class="text-center bg-light">{{ $customer->price +0 }}</td>
                            <td class="text-center bg-light">-</td>
                        </tr>
                </tbody>
            </table>
            <div class="mt-5">
                <p>
                    <strong> ผู้รับเงิน</strong> _________________________
                </p>
                <p>
                    <strong> วันที่</strong> &nbsp; &nbsp;&nbsp;________/________/________
                </p>
            </div>
        </div>
        <!-- ปุ่มพิมพ์ -->
        <div class="text-center my-5 no-print">
            <button class="btn btn-outline-primary shadow" onclick="window.print()">
                <i class="fa fa-print"></i> พิมพ์เอกสาร
            </button>
        </div>
    </div>
</body>
</html>