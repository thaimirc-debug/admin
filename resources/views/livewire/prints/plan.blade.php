<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>พิมพ์เอกสารลูกค้า - {{ $customer->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{asset('css/styles.css')}}?tme={{base64_encode(now())}}">
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
    <!-- หน้า 2 : ตารางนัดหมาย -->
    <div class="page">
        <div class="row" style="height: 1060px; border:0px solid #666;">
            <div class="col px-0">
                <div class="text-center p-3 mb-3" style="border:1px solid #666; background-color: lightblue;">
                    <h3>
                        PS Home care (Thailand) กำจัดปลวก (สาขา{{$customer->branch->name}})
                    </h3>
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
                                <th rowspan="2" class="text-center bg-light" width="12%"
                                    style="vertical-align: middle;">วันที่</th>
                                <th rowspan="2" class="text-center bg-light" style="vertical-align: middle;">
                                    รายละเอียดบริการ</th>
                                <th colspan="2" class="text-center bg-light" width="28%">ลายเซ็นต์</th>
                            </tr>
                            <tr>
                                <th class="text-center bg-light small" width="12%">ผู้ให้บริการ</th>
                                <th class="text-center bg-light small" width="14%">ลูกค้า</th>
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
                                    <small>{!! $g_date !!}</small>

                                    </td>
                                <td>{{ $appointment->description }}</td>
                                <td class="text-center">
                                    @if($appointment->is_done)
                                    <span class="text-primary">PsHome</span>
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
                                    @if($gn[0] != '') 
                                        @if ($appointment->signature_base64)
                                            <a href="{{ route('customer.sign', $appointment->id) }}">
                                                <img src="{{ $appointment->signature_base64 }}" alt="ลายเซ็น" style="max-width: 100%;">
                                            </a> 
                                        @else 
                                            <a href="{{ route('customer.sign', $appointment->id) }}">{{ $gn[0] }}</a> 
                                        @endif
                                    
                                    @else
                                        @if ($appointment->signature_base64)
                                            <a href="{{ route('customer.sign', $appointment->id) }}">
                                                <img src="{{ $appointment->signature_base64 }}" alt="ลายเซ็น" style="max-width: 100%;">
                                            </a> 
                                        @else 
                                            <a href="{{ route('customer.sign', $appointment->id) }}" class="text-muted no-print">ลงชื่อ</a> 
                                        @endif
                                    @endif
                                    {{-- <i class="text-primary">{!! $gn[0] !!}</i> --}}
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
</body>
</html>