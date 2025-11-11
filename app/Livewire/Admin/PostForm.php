<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;
    public $postId, $cats, $category_id, $title, $description, $keywords, $content, $image, $oldImage;
    public Post $post;
    public $is_published = false, $pin = false, $published_at, $active;

    protected $rules = [
        'description' => 'nullable|string|max:255',
        'keywords'    => 'nullable|string|max:255',
        'title'       => 'required|string|max:255',
        'content'     => 'required|string',
        'published_at'  => 'required|date_format:Y-m-d\TH:i',
        'image'       => 'nullable|image|max:2048',
    ];

    public function mount(Post $post) // เปลี่ยนจาก $id เป็น $post เพื่อใช้ Route Model Binding
    {
        $this->cats = Category::orderBy('position', 'asc')->get();
        if ($post->exists) {
            $this->postId      = $post->id;
            $this->title       = $post->title;
            $this->content     = $post->content;
            $this->category_id = $post->category_id;
            $this->description = $post->description;
            $this->keywords    = $post->keywords;
            $this->oldImage    = $post->image;
            $this->pin    = ($post->pin?'1':'0');
            $this->is_published = ($post->is_published?'1':'0');
            $this->published_at = $post->published_at
            ? Carbon::parse($post->published_at)->format('Y-m-d\TH:i')
            : now()->format('Y-m-d\TH:i');
        } else {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function save()
    {
        $this->validate();
        $imagePath = $this->oldImage;
        $imageName  = null;
        $publicPath = public_path('images');
        if (! is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        if ($this->image) {
            if ($this->oldImage) {
                if (file_exists(public_path('images/' . $this->oldImage))) {
                    @unlink(public_path('images/' . $this->oldImage));
                }
            }
            $imageName = time() . '.' . $this->image->getClientOriginalExtension();
            $imagePath = public_path('images/' . $imageName);
            $imageName = $this->resizeAndCropToWebp($this->image->getRealPath(), $imagePath, 640, 360);
        } else {
            $p = Post::find($this->postId);
            if ($p->image != null) {
                $imageName = $p->image;
            }
        }
        $this->category_id = $this->category_id == "null" ? null : $this->category_id;
        $postData = [
            'title'        => $this->title,
            'slug'         => str_slug($this->title),
            'description' => $this->description,
            'content'      => $this->content,
            'image'        => $imageName,
            'category_id'  => $this->category_id ?? null,
            'keywords'     => $this->keywords,
            'user_id'      => Auth::id(),
            'pin'     => (bool) $this->pin,
            'is_published' => (bool) $this->is_published, // แปลงเป็น boolean ปลอดภัย
            'published_at' => $this->parseDatetime($this->published_at),
        ];
        if ($this->postId) {
            Post::find($this->postId)->update($postData);
            session()->flash('message', 'แก้ไขโพสต์สำเร็จ!');
        } else {
            Post::create($postData);
            session()->flash('message', 'สร้างโพสต์สำเร็จ!');
            $this->reset(['title', 'content', 'image', 'category_id', 'description', 'keywords']);
            $this->dispatch('refreshEditor');
        }
        return redirect()->route('admin.posts.index');
    }

    private function parseDatetime($value)
    {
        try {
            return Carbon::parse($value); // รองรับทั้ง "Y-m-d\TH:i" และ format อื่นๆ
        } catch (\Exception $e) {
            return now(); // fallback ถ้า format ผิด
        }
    }

    public function resizeAndCropToWebp($sourcePath, $destinationPath, $width = 640, $height = 360)
    {
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $src = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $src = imagecreatefromwebp($sourcePath);
                break;
            default:
                throw new Exception('Unsupported image type');
        }
        $srcW      = imagesx($src);
        $srcH      = imagesy($src);
        $aspectSrc = $srcW / $srcH;
        $aspectDst = $width / $height;
        if ($aspectSrc > $aspectDst) {
            $newWidth  = $srcH * $aspectDst;
            $newHeight = $srcH;
            $srcX      = ($srcW - $newWidth) / 2;
            $srcY      = 0;
        } else {
            $newWidth  = $srcW;
            $newHeight = $srcW / $aspectDst;
            $srcX      = 0;
            $srcY      = ($srcH - $newHeight) / 2;
        }
        $dst = imagecreatetruecolor($width, $height);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
        imagecopyresampled($dst, $src, 0, 0, (int) $srcX, (int) $srcY, $width, $height, (int) $newWidth, (int) $newHeight);
        $filename   = time() . '.webp';
        $outputPath = public_path('images/' . $filename);
        imagewebp($dst, $outputPath, 100);
        imagedestroy($src);
        imagedestroy($dst);
        return $filename;
    }
    public function render()
    {
        return view('livewire.admin.post-form');
    }
}
