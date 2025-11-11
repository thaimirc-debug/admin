<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminIndex;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\PostManager;
use App\Livewire\Admin\PostForm;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\BranchManager;
use App\Livewire\Admin\CustomerManager;
use App\Livewire\Admin\AppointmentManager;

use App\Livewire\Home\PostShow;
use App\Livewire\Home\IndexManager;
use App\Livewire\Home\IndexCustomer;
use App\Livewire\Home\IndexCustomerList;
use App\Livewire\Home\CalendarManager;
use App\Livewire\Home\SignatureForm;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', AdminIndex::class)->name('admin.index');
    Route::get('/category', CategoryManager::class)->name('admin.category.index');
    Route::get('/posts', PostManager::class)->name('admin.posts.index');
    Route::get('/posts/create', PostForm::class)->name('admin.posts.create');
    Route::get('/posts/edit/{post}', PostForm::class)->name('admin.posts.edit');

    Route::get('/branchs', BranchManager::class)->name('admin.branchs.index');
    Route::get('/users', UserManager::class)->name('admin.users.index');
    Route::get('/customers', CustomerManager::class)->name('admin.customers.index');
    Route::get('/customer/{customer}/print', function (\App\Models\Customer $customer) {
        $customer->load('branch');
        $firstApp = \App\Models\Appointment::where('customer_id', $customer->id)
        ->orderBy('appointment_at', 'asc')->first();
        return view('livewire.prints.print', compact('customer','firstApp'));
    });

    Route::get('/appointments', AppointmentManager::class)->name('admin.appointments.index');
});

Route::get('/customers', IndexCustomerList::class)->name('customers.index');
Route::get('/customer/{customer}/view', IndexCustomer::class)->name('customer.view');
Route::get('/customer/{customer}/plan', function (\App\Models\Customer $customer) {
    $customer->load('branch');
    $firstApp = \App\Models\Appointment::where('customer_id', $customer->id)
    ->orderBy('appointment_at', 'asc')->first();
    return view('livewire.prints.plan', compact('customer','firstApp'));
});
// Route::get('/customer/{customer}/view', function (\App\Models\Customer $customer) {
//         $customer->load('branch');
//         $allApps = \App\Models\Appointment::where('customer_id', $customer->id)
//         ->orderBy('appointment_at', 'asc')->get();
//         return view('livewire.home.index-customer', compact('customer','allApps'));
//     })->name('customer.view');

Route::get('/customer/{appointmentId}/sign', SignatureForm::class)->name('customer.sign');
Route::get('/calendar', CalendarManager::class)->name('calendar.index');
Route::get('/posts/show/{post}', PostShow::class)->name('posts.show');
Route::post('/upload-image/{resize?}', [App\Http\Controllers\HomeController::class, 'upload'])->name('upload.image');

Route::get('/', IndexManager::class)->name('index');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
