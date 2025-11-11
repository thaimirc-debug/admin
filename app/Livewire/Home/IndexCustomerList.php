<?php
namespace App\Livewire\Home;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class IndexCustomerList extends Component
{
    use WithFileUploads;
    use WithPagination;
    public string $filterBranch = '';
    public $search              = '', $branches;

    public function mount()
    {

        $this->branches = Branch::all();

        // $this->customerApps = Appointment::with('customer')
        //     ->orderBy('appointment_at', 'asc')->get();

        // $this->customers = Customer::with('appointments')
        //     ->when($this->filterBranch, fn($q) =>
        //         $q->where('branch_id', $this->filterBranch)
        //     )
        //     ->when($this->search, function ($query) {
        //         $query->where(function ($q) {
        //             $q->where('name', 'like', '%' . $this->search . '%')
        //                 ->orWhere('address', 'like', '%' . $this->search . '%')
        //                 ->orWhere('province', 'like', '%' . $this->search . '%')
        //                 ->orWhere('phones', 'like', '%' . $this->search . '%');
        //         });
        //     })
        //     ->latest()
        //     ->paginate(10);
    }

    public function render()
    {
        $customerQuery = Customer::with('appointments')
            ->when($this->filterBranch, fn($q) =>
                $q->where('branch_id', $this->filterBranch)
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%')
                        ->orWhere('province', 'like', '%' . $this->search . '%')
                        ->orWhere('phones', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        return view('livewire.home.index-customer-list', [
            'customers'    => $customerQuery->paginate(10),
            'customerApps' => Appointment::with('customer')->orderBy('appointment_at', 'asc')->get(),
        ])->layout('livewire.layouts.index-app');
    }

    // public function render()
    // {
    //     return view('livewire.home.index-customer-list', ['branches' => Branch::all()])
    //     ->layout('livewire.layouts.index-app');
    // }
}
