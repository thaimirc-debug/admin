<?php

namespace App\Livewire\Home;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class IndexCustomer extends Component
{
    use WithFileUploads;
    use WithPagination;


    public $customer, $customerApps;
    
    public $isAppOpen                          = false;
    public $appointmentId;
    public $appointment_at, $description, $service, $is_done = false;
    public $selectedCustomerId;
    public $images = [];
   
    public $newImages = [];

    public function editApps($customerId, $appointmentId = null) {
        // dd($appointmentId);
        $this->selectedCustomerId = $customerId;
        $this->appointmentId      = $appointmentId;
        if ($appointmentId) {
            $appointment          = Appointment::with('images')->findOrFail($appointmentId);
            $this->appointment_at = $appointment->appointment_at;
            $this->description    = $appointment->description;
            $this->service        = $appointment->service;
            $this->is_done        = $appointment->is_done;
                    
            $this->reset(['images', 'newImages']); // แก้ไขตรงนี้เพื่อป้องกันการซ้ำ
            $this->images = $appointment->images->pluck('path')->toArray();
        } else {
            $this->resetAppointmentForm();
        }
        $this->isAppOpen = true;
    }

    public function saveAppointment()
    {
        $data = $this->validate([
            'appointment_at' => 'required|date',
            'description'    => 'required|string',
            'service'        => 'required|string',
            'is_done'        => 'boolean',
        ]);

        $data['customer_id'] = $this->selectedCustomerId;
        $publicPath = public_path('apps');
        if (! is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

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

        $this->dispatch('appointment-updated'); // แก้ไพร์ Livewire event
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
        imagewebp($image, $destination, 90);             
        imagedestroy($image);// เคลียร์หน่วยความจำ
        return $filename;
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

    public function closeAppModal()
    {
        $this->isAppOpen = false;
    }

    protected $listeners = ['appointment-updated' => 'loadAppointments'];

    public function loadAppointments()
    {
        $this->appointments = Appointment::where('customer_id', $this->selectedCustomerId)->get();
    }
    public function mount(Customer $customer)
    {
        $this->loadAppointments();
        $this->customer = $customer->load('branch');

        $this->customerApps = Appointment::where('customer_id', $customer->id)
            ->orderBy('appointment_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.home.index-customer',['branches'  => Branch::all()])
        ->layout('livewire.layouts.index-app');
    }
}
