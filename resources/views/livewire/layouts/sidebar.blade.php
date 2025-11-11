<a href="{{route('admin.index')}}" title="admin Controller">
    <h1 class="mb-3 text-dark"><i class="fa-solid fa-computer"></i> admin.<span class="text-secondary">info</span></h1>
</a>
<div class="list-group mb-3">
    <a href="{{route('admin.posts.index')}}" class="list-group-item list-group-item-action {{ Route::is('admin.posts.*') ? 'active' : '' }}">
        <i class="fa-solid fa-newspaper"></i> จัดการบทความ</a>
    <a href="{{route('admin.category.index')}}" class="list-group-item list-group-item-action {{ Route::is('admin.category.*') ? 'active' : '' }}">
        <i class="fa-solid fa-list"></i> จัดการหมวดหมู่</a>
    <a href="{{route('admin.users.index')}}" class="list-group-item list-group-item-action {{ Route::is('admin.users.*') ? 'active' : '' }}">
        <i class="fa-solid fa-person-through-window"></i> จัดการพนักงาน</a>
    <a href="{{route('admin.customers.index')}}" class="list-group-item list-group-item-action {{ Route::is('admin.customers.*') ? 'active' : '' }}">
        <i class="fa-solid fa-people-group"></i>
        จัดการข้อมูลลูกค้า</a>
    <a href="{{route('admin.branchs.index')}}" class="list-group-item list-group-item-action {{ Route::is('admin.branchs.*') ? 'active' : '' }}">
        <i class="fa-solid fa-code-branch"></i>
        จัดการข้อมูลสาขา</a>
    @auth
    <a class="list-group-item list-group-item-action" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-share-from-square"></i> ออกจากระบบ
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    @endauth
</div>