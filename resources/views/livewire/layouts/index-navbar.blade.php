<nav class="navbar navbar-expand-lg navbar-light navbar-transparent"
    style="position: fixed; top: 0; right: 3px; left: 0; z-index: 1030;">
    <div class="container">
        <a class="navbar-brand text-white" href="{{route('index')}}" title="pshome index">
            <img src="{{asset('pshome.png')}}" width="100" alt="" srcset="">
        </a>
        <div class="ms-auto">
            <button class="btn btn-blue rounded-2 shadow"
                style="opacity: 0.8; background-color: #023E94 !important; border:2px solid white !important;" data-bs-toggle="modal"
                data-bs-target="#menuModal">
                {{-- <i class="fa-solid fa-left-long"></i> --}}
                {{-- <i class="fa-solid fa-arrow-left"></i> --}}
                <i class="fa-solid fa-angles-left"></i>
            </button>

        </div>
    </div>
</nav>
<style>
    .trafic a:hover {
        background-color: #01419c;
    }
</style>
<!-- Modal -->

<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-end">
        <div class="modal-content border-0 rounded-3" style="background:transparent;">
            <div class="modal-body trafic rounded-4" style="padding:10px;">
                <div class="rounded-3 f-ibm p-1" style="border:2px solid white;">
                    <a href="{{route('index')}}" class="{{ Route::is('index') ? 'active' : '' }}">หน้าแรก</a>
                    <a href="#">สาระน่ารู้</a>
                    <a href="#">เด็กและสุขภาพ</a>
                    @auth
                    <a href="{{route('customers.index')}}" class="{{ Route::is('customers.*') ? 'active' : '' }}">
                        จัดการลูกค้า
                    </a>
                    <a href="{{route('calendar.index')}}" class="{{ Route::is('calendar.*') ? 'active' : '' }}">
                        ตารางนัดหมาย
                    </a>
                    @if(Auth::user()->level >=10)
                    <a href="{{route('admin.index')}}" class="{{ Route::is('admin.*') ? 'active' : '' }}">
                        แอดมินคอนโทรล
                    </a>
                    @endif
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-person-through-window"></i>
                        ออกจากระบบ
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @else
                    <a href="{{route('login')}}">
                        เข้าสู่ระบบ
                    </a>
                    @endauth
                    <a href="#">รายงาน</a>
                    <a href="#">ตั้งค่า</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-end">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="menuModalLabel"><i class="fa-solid fa-rectangle-list"></i> เมนูคอนโทรล</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:0 4px;">
                <div class="list-group list-group-flush">
                    <a href="{{route('index')}}"
                        class="list-group-item list-group-item-action rounded {{ Route::is('index') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        หน้าแรก</a>

                    @auth
                    <a href="{{route('calendar.index')}}"
                        class="list-group-item list-group-item-action {{ Route::is('calendar.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-id-card"></i>
                        ตารางนัดหมาย
                    </a>
                    <a href="{{route('customers.index')}}"
                        class="list-group-item list-group-item-action {{ Route::is('customers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-computer"></i>
                        รายชื่อลูกค้า
                    </a>
                    @if(Auth::user()->level >=10)
                    <a href="{{route('admin.index')}}"
                        class="list-group-item list-group-item-action {{ Route::is('admin.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-computer"></i>
                        แอดมินคอนโทรล
                    </a>
                    @endif
                    <a class="list-group-item list-group-item-action" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-person-through-window"></i>
                        ออกจากระบบ
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @else
                    <a href="{{route('login')}}" class="list-group-item list-group-item-action">
                        <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i>
                        เข้าสู่ระบบ
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div> --}}