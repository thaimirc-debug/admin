<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    protected $shouldResizeAndCrop = false; // กำหนดค่าเริ่มต้นว่าจะ Resize และ Crop หรือไม่
    public function upload(Request $request, $resize = true): JsonResponse
    {
        // *** ตั้งค่า $shouldResizeAndCrop จาก Route
        // Route::post('/upload-image/{resize?}', [UploadController::class, 'upload']); ***
        // $this->shouldResizeAndCrop = $resize; 
        if (!$request->hasFile('files')) {
            return response()->json(['error' => true, 'msg' => 'No files were uploaded.'], 400);
        }
        $uploadedFiles = [];
        $path = 'uploads';
        $publicPath = public_path($path);
        // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        foreach ($request->file('files') as $file) {
            $extension = $file->getClientOriginalExtension();
            // $filename = Str::uuid() . '.' . $extension;
            $filename = Str::uuid() . '.webp';
            $fullPath = $publicPath . '/' . $filename;
            // โหลดภาพ
            $image = @imagecreatefromstring(file_get_contents($file));
            if (!$image) {
                continue;
            }
            if ($this->shouldResizeAndCrop) {
                $image = resizeAndCropImage($image);
                if ($image) {
                    // imagejpeg($image, $fullPath, 90);
                    imagewebp($image, $fullPath, 90); // แปลงเป็น WebP
                    imagedestroy($image);
                } else {
                    // หาก resizeAndCropImage ล้มเหลว ให้บันทึกภาพต้นฉบับ
                    $file->move($publicPath, $filename);
                }
            } else {
                // บันทึกภาพต้นฉบับโดยไม่มีการ Resize และ Crop
                $file->move($publicPath, $filename);
            }
            $uploadedFiles[] = $filename;
        }
        return response()->json([
            'files' => $uploadedFiles,
            'path' => $path . '/',
            'baseurl' => asset('/'),
            'error' => false,
            'msg' => 'Images uploaded successfully!',
        ]);
    }
}
