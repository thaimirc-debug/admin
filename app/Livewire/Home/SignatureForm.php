<?php

namespace App\Livewire\Home;

use App\Models\Customer;
use Livewire\Component;
use App\Models\Appointment;

class SignatureForm extends Component
{
    public string $signatureData = ''; // base64

    public int $appointmentId;

    public function mount($appointmentId)
    {
        $this->appointmentId = $appointmentId;
    }

    public function save()
    {
        if (!$this->signatureData || strlen($this->signatureData) < 100) {
            $this->addError('signatureData', 'กรุณาเซ็นชื่อก่อน');
            return;
        }

        $appointment = Appointment::findOrFail($this->appointmentId);
        $appointment->signature_base64 = $this->signatureData;
        $appointment->save();
        return redirect('/customer/' . $appointment->customer_id . '/plan');
    }

    public function deleteSignature()
    {
        $appointment = Appointment::findOrFail($this->appointmentId);
        $appointment->signature_base64 = null;
        $appointment->save();

        session()->flash('message', 'ลบลายเซ็นต์เรียบร้อยแล้ว');
    }
    
    public function render()
    {
        $appointment = Appointment::with('customer')->findOrFail($this->appointmentId);
        $customer = $appointment->customer;

        return view('livewire.home.signature-form', compact('appointment', 'customer'))
        ->layout('livewire.layouts.index-app');
    }
}
