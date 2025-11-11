<?php

namespace App\Livewire\Home;

use App\Models\Post;
use Livewire\Component;
use App\Models\Category;

class PostShow extends Component
{
       public $post;

    public function mount(Post $post)
    {
        // dd($id);
        $this->post = $post;
        $this->countViewOnce();
    }

    protected function countViewOnce()
    {
        $key = 'post_' . $this->post->id . '_viewed';

        if (!session()->has($key)) {
            $this->post->increment('views');
            session()->put($key, true);
        }
    }

    public function render()
    {
        $categories = Category::all();
        $fposts = Post::where('is_published',true)->where('published_at', '<=', now())->where('pin',true)->OrderBy('published_at','desc')->get();
        return view('livewire.home.post-show',compact('categories','fposts'))->layout('livewire.layouts.index-app');
        // return view('livewire.home.post-show');
    }
}
