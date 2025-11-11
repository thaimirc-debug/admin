<?php
namespace App\Livewire\Home;

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Component;
use Auth;

class CalendarManager extends Component
{
    public $selectedAppointments = [];
                         // Property สำหรับเก็บข้อมูลนัดหมายทั้งหมดที่ถูกเลือก
    public $selectedDay; // Property สำหรับเก็บวันที่ที่ถูกเลือก
    public Carbon $currentDate;
    public $appointments; // Property สำหรับเก็บข้อมูลนัดหมาย
    public $isOpen             = false;
    public $expandedCustomerId = null; // เพิ่ม Property นี้

    public function mount()
    {
    if (!Auth::check() || Auth::user()->level < 1) {
        return redirect()->route('login');
    }
        $this->currentDate = Carbon::now()->startOfMonth();
        $this->loadAppointments();
    }

    public function goToPreviousMonth()
    {
        $this->currentDate->subMonthNoOverflow()->startOfMonth();
        $this->loadAppointments();
    }

    public function goToNextMonth()
    {
        $this->currentDate->addMonthNoOverflow()->startOfMonth();
        $this->loadAppointments();
    }

    // protected function loadAppointments()
    // {
    //     $this->appointments = Appointment::with(['customer', 'images']) // เพิ่ม with('customer')
    //     ->whereMonth('appointment_at', $this->currentDate->month)
    //     ->whereYear('appointment_at', $this->currentDate->year)
    //     ->get()
    //     ->keyBy(function ($item) {
    //         return Carbon::parse($item->appointment_at)->day;
    //     });
    // }

    protected function loadAppointments()
    {
        $query = Appointment::with(['customer', 'images'])
            ->whereMonth('appointment_at', $this->currentDate->month)
            ->whereYear('appointment_at', $this->currentDate->year);

        // Add branch filter if user level < 99 and has branch_id
        if (Auth::user()->level < 99 && Auth::user()->branch_id != null) {
            $query->whereHas('customer', function ($q) {
                $q->where('branch_id', Auth::user()->branch_id);
            });
        }

        $this->appointments = $query->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->appointment_at)->day;
            });
        }

    public function showAppointmentModal($day)
    {
        $appointments = Appointment::with(['customer', 'images']) // เพิ่ม 'images'
            ->whereDay('appointment_at', $day)
            ->whereMonth('appointment_at', $this->currentDate->month)
            ->whereYear('appointment_at', $this->currentDate->year)
            ->orderBy('appointment_at', 'asc') // เพิ่มการเรียงลำดับตามเวลา
            ->get();

        $this->selectedAppointments = $appointments->toArray(); // แปลงผลลัพธ์เป็น Array เสมอ
        $this->selectedDay          = $day;
        $this->isOpen               = true;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }
    public function closeModal()
    {
        $this->isOpen               = false;
        $this->selectedAppointments = [];
    }

    protected function getThaiMonthYear(): string
    {
        $thaiMonths = [
            1  => 'มกราคม',
            2  => 'กุมภาพันธ์',
            3  => 'มีนาคม',
            4  => 'เมษายน',
            5  => 'พฤษภาคม',
            6  => 'มิถุนายน',
            7  => 'กรกฎาคม',
            8  => 'สิงหาคม',
            9  => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];
        $monthNumber   = $this->currentDate->month;
        $thaiMonthName = $thaiMonths[$monthNumber];
        $thaiYear      = $this->currentDate->year + 543;
        return $thaiMonthName . ' ' . $thaiYear;
    }

    public function render()
    {
        $daysInMonth     = $this->currentDate->daysInMonth;
        $firstDayOfMonth = $this->currentDate->copy()->startOfMonth()->dayOfWeekIso;
        $lastDayOfMonth  = $this->currentDate->copy()->endOfMonth()->dayOfWeekIso;

        $days = [];
        for ($i = 1; $i < $firstDayOfMonth; $i++) {
            $days[] = '';
        }

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $days[] = $i;
        }

        for ($i = $lastDayOfMonth; $i < 7; $i++) {
            $days[] = '';
        }

        return view('livewire.home.calendar-manager', [
            'days'      => array_chunk($days, 7),
            'monthName' => $this->getThaiMonthYear(),
        ])->layout('livewire.layouts.index-app');

    }
}
