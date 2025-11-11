<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class AppointmentManager extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $appointmentId, $customer_id, $service, $description, $appointment_at, $is_done = false;
    public $images = [];
    public $fileInputKey;
    public $isEditMode = false, $isOpen = 0;
    public $search = '';

    public $year;
    public $month;

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'service'        => 'required|string|max:255',
        'description'    => 'nullable|string',
        'appointment_at' => 'required|date',
        'is_done'        => 'boolean',
        'images.*'       => 'nullable|image|max:2048',
    ];
    
    public function create() {
        $this->reset();
        $this->openModal();
    }
    public function openModal() {
        $this->isOpen = true;
    }
    public function closeModal() {
        $this->isOpen = false;
    }

    public function save()
    {

        $data = $this->validate();
        $appointment = $this->isEditMode
            ? Appointment::findOrFail($this->appointmentId)->update($data)
            : Appointment::create($data);
    
        // ถ้าเป็นการเพิ่ม
        if (! $this->isEditMode) {
            $appointment = Appointment::latest()->first();
        }
    
        // จัดการอัปโหลดรูป
        // foreach ($this->images ?? [] as $image) {
        //     $path = $image->store('appointments', 'public');
        //     $appointment->images()->create(['path' => $path]);
        // }
    
        $this->resetForm();
        session()->flash('message', 'บันทึกนัดหมายเรียบร้อยแล้ว!');
    }



    public function upcomingAppointmentsThisMonth()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $appointments = Appointment::where('is_done', false)
            ->whereBetween('appointment_at', [$startOfMonth, $endOfMonth])
            ->orderBy('appointment_at', 'asc')
            ->get();

        return $appointments;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMonth()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $now = Carbon::now();
        // $startOfMonth = $now->copy()->startOfMonth();
        // $endOfMonth = $now->copy()->endOfMonth();

        $startOfMonth = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();


        return view('livewire.admin.appointment-manager', [
            'appointments' => Appointment::with('customer')
            // ->where('is_done', false)
            ->whereBetween('appointment_at', [$startOfMonth, $endOfMonth])
            ->orderBy('appointment_at', 'asc')
            ->paginate(4),
            'customers' => Customer::all(),
        ]);
    }

    // public function render()
    // {
    //     return view('livewire.admin.appointment-manager');
    // }
}
