<?php

namespace App\Livewire\Admin;

use Auth;
use Livewire\Component;
use App\Models\Category;
use App\Models\Post;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PostManager extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $search='', $post, $postId, $title, $content, $tag, $image, $oldImage;

    public function mount(Post $post)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->postId     = $post->id;
        $this->title      = $post->title;
        $this->content    = $post->content;
        $this->category_id = $post->category_id;
        $this->description = $post->description;
        $this->keywords    = $post->keywords;
        $this->oldImage    = $post->image;
        $this->is_published = $post->is_published ?? false;
        $this->published_at = $post->published_at ?? now();
        $this->dispatch('refreshEditor');
    }

    public function delete(Post $post)
    {
        // ลบรูปภาพที่เกี่ยวข้อง (ถ้ามี)
        if ($post->image) {
            if (file_exists(public_path('images/' . $post->image))) {
                @unlink(public_path('images/' . $post->image));
            }
        }
        $post->delete();
        session()->flash('message', 'ลบโพสต์สำเร็จ!');
    }    

    public function render()
    {
// return view('livewire.admin.post-manager');
        return view('livewire.admin.post-manager',[
            'posts'=>Post::where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->latest('published_at')
            ->paginate(9)
        ]);
    }
}
