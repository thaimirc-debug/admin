<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;

class IndexManager extends Component
{
    public $perPage = 5; // โหลดครั้งแรก 6 โพสต์

    public function loadMore()
    {
        $this->perPage += 5; // เพิ่มอีก 6 โพสต์ตอนกด Load More
    }

    public function render()
    {
        $categories = Category::all();  // ดึงหมวดหมู่ทั้งหมดจากฐานข้อมูล
        $posts = Post::where('is_published',true)->where('published_at', '<=', now())->where('pin',false)->latest()->paginate($this->perPage);  // ดึงโพสต์
        $fposts = Post::where('is_published',true)->where('published_at', '<=', now())->where('pin',true)->OrderBy('published_at','desc')->get();
        return view('livewire.home.index-manager', compact('categories','fposts','posts'))
        ->layout('livewire.layouts.index-app');
    }
}
