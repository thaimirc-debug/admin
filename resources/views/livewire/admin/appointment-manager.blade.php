<div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <a href="{{route('admin.index')}}" title="admin Controller">
                    <h1 class="mb-3"><i class="fa-solid fa-computer"></i> db.<span class="text-warning">in</span>fo</h1>
                </a>
                <div class="list-group mb-3">
                    <a href="{{route('admin.posts.index')}}" class="list-group-item list-group-item-action"><i
                            class="fa-solid fa-newspaper"></i> จัดการบทความ</a>
                    <a href="{{route('admin.category.index')}}" class="list-group-item list-group-item-action"><i
                            class="fa-solid fa-list"></i> จัดการหมวดหมู่</a>
                    <a href="{{route('admin.users.index')}}" class="list-group-item list-group-item-action"><i
                            class="fa-solid fa-person-through-window"></i> จัดการพนักงาน</a>
                    <a href="{{route('admin.customers.index')}}" class="list-group-item list-group-item-action"><i
                            class="fa-solid fa-people-group"></i>
                        จัดการข้อมูลลูกค้า</a>
                    <a href="{{route('admin.appointments.index')}}" class="list-group-item list-group-item-action active">
                        <i class="fa-solid fa-calendar-days"></i> จัดการข้อมูลนัดหมาย</a>
                    @auth
                    <a class="list-group-item list-group-item-action" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-share-from-square"></i> ออกจากระบบ</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @endauth
                </div>
            </div>
            <div class="col-lg-9">


                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button wire:click="previousMonth" class="btn btn-outline-secondary btn-sm">« เดือนก่อน</button>
                    <h5 class="mb-0">
                        {{ 
                        \Carbon\Carbon::create($year, $month)->locale('th')->translatedFormat('F')
                        }}
                        {{ 
                        \Carbon\Carbon::create($year, $month)->locale('th')->translatedFormat('Y')+543
                        }}
                    </h5>
                    <button wire:click="nextMonth" class="btn btn-outline-secondary btn-sm">เดือนถัดไป »</button>
                </div>


                <div class="row">
                    <div class="col-6">
                        {{-- <button wire:click="create" class="mb-3 btn btn-info">เพิ่มข้อมูลลูกค้าใหม่ <i
                                class="fa-solid fa-indent"></i></button> --}}
                    </div>
                    <div class="col-6 mb-3">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
                            class="form-control rounded border px-4 py-2" />
                    </div>
                </div>

                <div class="row">
                    @forelse ($appointments ?? [] as $app)
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="text-secondary"><i class="fa-solid fa-calendar-days text-danger"></i>
                                    @if ($app->appointment_at)
                                    {{ \Carbon\Carbon::parse($app->appointment_at)->format('H:i:s') != '00:00:00'
                                    ? \Carbon\Carbon::parse($app->appointment_at)->format('d/m/Y H:i')
                                    : \Carbon\Carbon::parse($app->appointment_at)->format('d/m/Y') }}
                                    @endif
                                </h4>                                
                            </div>
                            <div class="card-body">

                                <h4>ชื่อ {{ $app->customer->name }}</h4>
                                <div class="p-2 rounded mb-3" style="background-color: azure; border:1px solid #e3f2f8;">
                                    <p>
                                       <strong>ที่อยู่</strong> {{ $app->customer->address }} จ.{{ $app->customer->province }}
                                    </p>
                                    <p>
                                        <strong>โทรศัพท์</strong>
                                        @foreach ($app->customer->phones ?? [] as $p)
                                        <span class="small">{{($loop->iteration > 1) ? ',':''}}{{ $p }}</span>
                                        @endforeach
                                    </p>
                                </div>
                                <p class="mb-3 text-secondary"><i class="fa-solid fa-star-of-life text-secondary"></i> {{ $app->description }}</p>

                                @php
                                $statusHtml = $app->is_done
                                ? '<span
                                    class="bg-success text-white py-1 px-2 rounded small"><i class="fa-solid fa-circle-check"></i> เสร็จเรียบร้อย</span>'
                                : '<span
                                    class="bg-warning text-white py-1 px-2 rounded small"><i class="fa-solid fa-clock"></i> รอดำเนินการ..</span>';
                                @endphp
                                <div class="">{!! $statusHtml !!}</div>


                                {{-- @foreach ($app->images as $image)
                                <img src="{{ asset('uploads/' . $image->filename) }}" width="120" />
                                @endforeach --}}

                                {{-- @if ($app->getFirstMediaUrl('images'))
                                <img src="{{ $app->getFirstMediaUrl('images') }}" width="120" />
                                @endif --}}
                            </div>
                            <div class="card-footer">
                                <div class="my-2 text-end">
                                    <button wire:click="edit({{ $app->id }})"
                                        class="btn btn-secondary btn-sm">
                                        <i class="fa-solid fa-computer"></i> อัพเดทการนัดหมาย</button>
                                    <button wire:click="delete({{ $app->id }})"
                                        class="btn btn-danger btn-sm"><i class="fa-regular fa-trash-can"></i> ลบการนัดหมาย</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">ไม่มีรายการนัดหมาย</p>
                    @endforelse

                    @if ($appointments && $appointments->hasPages())
                    <div class="py-3">
                        {{ $appointments->links() }}
                    </div>
                    @endif


                    {{-- @include('livewire.partials.appointment-modal') --}}
                    <!-- Modal -->

                    <button class="btn btn-primary" wire:click="create">
                        เพิ่มนัดหมาย
                    </button>
                </div>
            </div>
        </div>
        @if ($isOpen)
        <div class="modal show" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content text-bg-white shadow">
                    <form wire:submit.prevent="save">
                        <div class="modal-header bg-light mb-2">
                            <div class="col-6">
                                <h5 style="font-size:25px; font-width:600;">
                                    <h5 class="modal-title">{{ $isEditMode ? 'แก้ไข' : 'เพิ่ม' }} นัดหมาย</h5>
                                </h5>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn-close" wire:click="closeModal"></button>
                            </div>
                        </div>
                        <div class="modal-body">

                            <div>
                                <input type="text" wire:model="service" placeholder="บริการ" class="form-control mb-2">
                                <textarea wire:model="description" class="form-control mb-2"
                                    placeholder="ลักษณะงาน"></textarea>
                                <input type="datetime-local" wire:model="appointment_at" class="form-control mb-2">
                                <div class="form-check">
                                    <input type="checkbox" wire:model="is_done" class="form-check-input" id="is_done">
                                    <label class="form-check-label" for="is_done">เสร็จสิ้น</label>
                                </div>


                                {{-- <div class="form-group mb-3">
                                    <label for="image">รูปภาพประกอบ: auto resize and crop 640x360px</label>
                                    <input type="file" wire:model="images" multiple class="form-control mb-2">

                                    @error('images') <span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    @if ($images)
                                    Photo Preview: auto resize and crop 640x360px
                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail">
                                    @elseif ($postId && $post = \App\Models\Post::find($postId))
                                    @if ($post->images)
                                    Current Photo:
                                    <img src="{{ asset($post->images) }}" class="img-thumbnail">
                                    @endif
                                    @endif
                                </div> --}}
                                <input type="file" wire:model="images" multiple class="form-control mb-2"
                                    :key="$fileInputKey">

                                <div class="row mt-2">
                                    @foreach ($images as $key => $image)
                                    <div class="position-relative">
                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded border" />
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        @endif
    </div>