<?php
namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CustomerManager extends Component
{
    public $name, $address, $province, $phones = [], $phoneInput;
    public $isOpen                             = false;
    public $isAppOpen                          = false;
    public $search                             = '';
    public $customerId;
    public $isEditMode = false;
    public $start_date, $price, $branch_id, $packet;
    public string $job_description = '';
    public array $jobSuggestions = [
        'อัดน้ำยาป้องกันปลวก* ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน รวมเซอร์วิส 2ครั้ง/ปี',
        'อัดน้ำยาป้องกันปลวก* ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน รวมเซอร์วิส 4ครั้ง/ปี',
        'กำจัดปลวกด้วยระบบสถานีเหยื่อภายในตัวบ้าน เข้าตรวจสถานีเหยื่อทุก 15วัน จนปลวกตายยกรัง 
        ฉีดพ่นน้ำยาป้องกันปลวกรอบๆบ้าน(หลังจากปลวกตายยกรัง) ทุก 3เดือน ตลอดสัญญา 1ปี',
    ];

    public $expandedCustomerId = null;

    public $appointmentId;
    public $appointment_at, $description, $service, $is_done = false;
    public $selectedCustomerId;
    public $images = [];

    use WithFileUploads;
    use WithPagination;
    // public $branches;
    public string $filterBranch = '';

    public function createAppAuto($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        if ($customer->packet == 1) {
            $appointments = [
                ['service' => 'เริ่มงานระบบน้ำยา', 'description' => 'อัดน้ำยาป้องกันปลวก* ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 0],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 6],
            ];
        } elseif ($customer->packet == 2) {
            $appointments = [
                ['service' => 'เริ่มงานระบบน้ำยา', 'description' => 'อัดน้ำยาป้องกันปลวก* ฉีดน้ำพ่นยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 0],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 3],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 6],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 9],
            ];
        } elseif ($customer->packet == 3) {
            $appointments = [
                ['service' => 'เริ่มงานระบบเหยื่อ', 'description' => 'วางเหยื่อ*', 'months' => 0],
                ['service' => 'ระบบเหยื่อ', 'description' => 'ตรวจเช็ค', 'days' => 15],
                ['service' => 'ระบบเหยื่อ', 'description' => 'ตรวจเช็ค', 'days' => 30],
                ['service' => 'ระบบเหยื่อ', 'description' => 'ตรวจเช็ค', 'days' => 45],
                ['service' => 'ระบบเหยื่อ', 'description' => 'ตรวจเช็ค', 'days' => 60],
                ['service' => 'ระบบน้ำยา', 'description' => 'อัดน้ำยาป้องกันปลวก* ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 3],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 6],
                ['service' => 'ระบบน้ำยา', 'description' => 'ตรวจเช็ค ฉีดพ่นน้ำยาป้องกันปลวกภายใน และรอบๆบ้าน', 'months' => 9],
            ];
        }

        foreach ($appointments as $data) {
            $baseDate = Carbon::parse($customer->start_date);
            if (isset($data['months'])) {
                $appointmentDate = $baseDate->copy()->addMonths($data['months']);
            } elseif (isset($data['days'])) {
                $appointmentDate = $baseDate->copy()->addDays($data['days']);
            } else {
                $appointmentDate = $baseDate;
            }

            Appointment::create([
                'customer_id'    => $customerId,
                'service'        => $data['service'],
                'description'    => $data['description'],
                'appointment_at' => $appointmentDate,
                'is_done'        => false,
            ]);
        }
        session()->flash('message', 'สร้างรายการนัดหมาย เรียบร้อยแล้ว');
    }

    public function closeAppModal()
    {
        $this->isAppOpen = false;
    }

    public function openAppModal($customerId, $appointmentId = null)
    {
        $this->selectedCustomerId = $customerId;
        $this->appointmentId      = $appointmentId;

        if ($appointmentId) {
            $appointment          = Appointment::with('images')->findOrFail($appointmentId);
            $this->appointment_at = $appointment->appointment_at;
            $this->description    = $appointment->description;
            $this->service        = $appointment->service;
            $this->is_done        = $appointment->is_done;
            $this->images         = $appointment->images->pluck('path')->toArray();
        } else {
            $this->resetAppointmentForm();
        }

        $this->isAppOpen = true;
    }

    public function deleteAppModal($customerId, $appointmentId)
    {
        $appointment = Appointment::with('images')->where('customer_id', $customerId)
            ->where('id', $appointmentId)
            ->first();

        if (! $appointment) {
            session()->flash('error', 'ไม่พบนัดหมายที่ต้องการลบ');
            return;
        }
        // 🔥 ลบภาพทุกภาพใน folder apps/
        foreach ($appointment->images as $image) {
            $fullPath = public_path('apps/' . $image->path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $image->delete(); // ลบ record
        }

        // 🔥 ลบนัดหมาย
        $appointment->delete();

        session()->flash('message', 'ลบนัดหมายและภาพทั้งหมดเรียบร้อยแล้ว');
        $this->dispatch('appointmentDeleted');
    }

    public function resetAppointmentForm()
    {
        $this->appointmentId  = null;
        $this->appointment_at = '';
        $this->description    = '';
        $this->service        = '';
        $this->is_done        = false;
        $this->images         = [];
    }

    public function saveAppointment()
    {
        $data = $this->validate([
            'appointment_at' => 'required|date',
            'description'    => 'required|string',
            'service'        => 'required|string',
            'is_done'        => 'boolean',
            // 'images.*'       => 'nullable|image|max:2048', // 2MB max ต่อไฟล์
        ]);

        $data['customer_id'] = $this->selectedCustomerId;

        if ($this->appointmentId) {
            $appointment = Appointment::find($this->appointmentId);
            $appointment->update($data); // <<== อัปเดตเฉยๆ ไม่ทับตัวแปร
            session()->flash('message', 'แก้ไขนัดหมายเรียบร้อยแล้ว');
        } else {
            $appointment = Appointment::create($data); // <<== ตรงนี้ต้องเก็บ appointment ไว้
            session()->flash('message', 'เพิ่มนัดหมายใหม่เรียบร้อยแล้ว');
        }

        if ($this->images && is_array($this->images)) {

            foreach ($this->images as $image) {
                if (is_string($image)) {
                    continue;
                }

                $imageName = $this->addToWebp($image->getRealPath());

                $appointment->images()->create([
                    'path' => $imageName,
                ]);
            }
        }

        $this->resetAppointmentForm();
        $this->isAppOpen = false;
        session()->flash('success', 'บันทึกข้อมูลสำเร็จ');
    }

    public function deleteImage($imagePath)
    {
        if (! $this->appointmentId) {
            return;
        }
        // ลบไฟล์จริง
        $fullPath = public_path('apps/' . $imagePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        // ลบ record ใน database
        $appointment = Appointment::find($this->appointmentId);
        $appointment->images()->where('path', $imagePath)->delete();
        // อัปเดต images array
        $this->images = $appointment->images()->pluck('path')->toArray();
    }

    public function removeTempImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // รีเซ็ต index ให้เรียงใหม่
        }
    }

    public function addToWebp($path)
    {

        $filename    = Str::uuid() . '.webp';
        $destination = public_path('apps/' . $filename);
        $imageInfo   = getimagesize($path);
        $mime        = $imageInfo['mime'];
        if ($mime === 'image/jpeg') {
            $image = imagecreatefromjpeg($path);
            // หมุนภาพตาม EXIF
            if (function_exists('exif_read_data')) {
                $exif = @exif_read_data($path);
                if (! empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $image = imagerotate($image, 180, 0);
                            break;
                        case 6:
                            $image = imagerotate($image, -90, 0);
                            break;
                        case 8:
                            $image = imagerotate($image, 90, 0);
                            break;
                    }
                }
            }
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($path);
        } else {
            copy($path, $destination);
            return $filename; // ไม่รองรับชนิดไฟล์อื่น
        }
                                             // บันทึกเป็น .webp
        imagewebp($image, $destination, 90); // ค่า 85 = คุณภาพ
                                             // เคลียร์หน่วยความจำ
        imagedestroy($image);
        return $filename;
    }

    public function create()
    {
        $this->reset();
        $this->openModal();
    }

    protected $rules = [

        'name'            => 'required|string|max:255',
        'address'         => 'nullable|string',
        'province'        => 'nullable|string|max:100',
        'phones.*'        => 'nullable|string|max:20',
        'start_date'      => 'nullable|date',
        'job_description' => 'nullable|string|max:255',
        'packet'          => 'nullable|numeric|min:0',
        'price'           => 'nullable|numeric|min:0',
        'branch_id'       => 'required|exists:branches,id',
    ];

    public function mount()
    {
        $this->phones = [''];
    }

    public function addPhone()
    {
        $this->phones[] = '';
    }

    public function removePhone($index)
    {
        unset($this->phones[$index]);
        $this->phones = array_values($this->phones);
    }

    public function edit(Customer $customer)
    {
        $this->customerId      = $customer->id;
        $this->name            = $customer->name;
        $this->address         = $customer->address;
        $this->province        = $customer->province;
        $this->phones          = $customer->phones ?? [''];
        $this->start_date      = optional($customer->start_date)->format('Y-m-d');
        $this->job_description = $customer->job_description;
        $this->packet          = $customer->packet;
        $this->price           = $customer->price;
        $this->branch_id       = $customer->branch_id;
        $this->isEditMode      = true;
        $this->openModal();
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->isEditMode && $this->customerId) {
            $customer = Customer::findOrFail($this->customerId);
            $customer->update($data);
        } else {
            $customer = Customer::create($data);
        }

        session()->flash('message', 'บันทึกข้อมูลลูกค้าสำเร็จ!');
        $this->closeModal();
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
        session()->flash('message', 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว!');
    }

    public function resetForm()
    {
        $this->reset([
            'customerId',
            'name',
            'address',
            'province',
            'phones',
            'start_date',
            'job_description',
            'packet',
            'price',
            'branch_id',
            'isEditMode',
        ]);
        $this->phones = [''];
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
{
    $user = auth()->user();
    
    $customers = Customer::with('appointments')
        ->when($user->level <= 10, function($query) use ($user) {
            // สำหรับผู้ใช้ระดับ ≤ 10 แสดงเฉพาะสาขาของตัวเอง
            return $query->where('branch_id', $user->branch_id);
        })
        ->when($user->level > 10 && $this->filterBranch, function($query) {
            // สำหรับผู้ใช้ระดับ > 10 และมีการเลือกสาขา
            return $query->where('branch_id', $this->filterBranch);
        })
        ->when($this->search, function ($query) {
            // การค้นหาข้อมูล
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('province', 'like', '%' . $this->search . '%')
                    ->orWhereJsonContains('phones',$this->search);
            });        
        })
        ->orderBy('start_date','desc')
        ->paginate(10);

    return view('livewire.admin.customer-manager', [
        'customers' => $customers,
        'branches' => Branch::when($user->level <= 10, function($query) use ($user) {
                        // สำหรับผู้ใช้ระดับ ≤ 10 แสดงเฉพาะสาขาของตัวเอง
                        return $query->where('id', $user->branch_id);
                    })->get()
    ]);
}

    // public function render()
    // {
    //     return view('livewire.admin.customer-manager', [
    //         'customers' => Customer::with('appointments')
    //             ->when($this->filterBranch, fn($q) =>
    //                 $q->where('branch_id', $this->filterBranch)
    //             )
    //             ->when($this->search, function ($query) {
    //                 $query->where(function ($q) {
    //                     $q->where('name', 'like', '%' . $this->search . '%')
    //                         ->orWhere('address', 'like', '%' . $this->search . '%')
    //                         ->orWhere('province', 'like', '%' . $this->search . '%')
    //                         ->orWhere('phones', 'like', '%' . $this->search . '%');
    //                 });
    //             })
    //             ->latest()
    //             ->paginate(10),

    //         'branches'  => Branch::all(),
    //     ]);
    // }

}
