<?php
if (! function_exists('str_slug')) {
    function str_slug($title, $separator = '-')
    {
        $title  = str_replace(['“', '”', '‘', '’', '–'], ['"', '"', "'", "'", "-"], trim($title));
        $string = preg_replace('/[^a-z0-9ก-๙เแ]/i', $separator, $title);
        $string = preg_replace('/-+/', $separator, $string);
        $string = preg_replace('/-$|^-/', '', $string);
        return mb_strtolower($string, 'UTF-8');
    }
}

if (! function_exists('resizeAndCropImage')) {
    function resizeAndCropImage($image, $width = 640, $height = 360)
    {
        $srcWidth  = imagesx($image);
        $srcHeight = imagesy($image);
        $dstWidth  = $width;
        $dstHeight = $height;
        $dst       = imagecreatetruecolor($dstWidth, $dstHeight);

        // Preserve transparency
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);

        // คำนวณ crop ตรงกลาง
        $aspectSrc = $srcWidth / $srcHeight;
        $aspectDst = $dstWidth / $dstHeight;

        if ($aspectSrc > $aspectDst) {
            $newHeight = $srcHeight;
            $newWidth  = $srcHeight * $aspectDst;
            $srcX      = ($srcWidth - $newWidth) / 2;
            $srcY      = 0;
        } else {
            $newWidth  = $srcWidth;
            $newHeight = $srcWidth / $aspectDst;
            $srcX      = 0;
            $srcY      = ($srcHeight - $newHeight) / 2;
        }
        // Resize & crop
        if (imagecopyresampled(
            $dst, $image, 0, 0,
            (int) $srcX, (int) $srcY, $dstWidth, $dstHeight,
            (int) $newWidth, (int) $newHeight
        )) {
            imagedestroy($image);
            return $dst;
        } else {
            imagedestroy($dst);
            return null;
        }
    }
}

if (! function_exists('thai_date')) {
    function thai_date($date, $format = 'j M Y เวลา H:i')
    {
        $months = [
            '', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
            'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.',
        ];

        $carbon = \Carbon\Carbon::parse($date);
        $day    = $carbon->format('j');
        $month  = $months[(int) $carbon->format('n')];
        // $year   = $carbon->format('Y') + 543;
        $year = $carbon->format('y') + 43;
        $time = $carbon->format('H:i');

        return "{$day} {$month} {$year}";
        // return "{$day} {$month} {$year} เวลา {$time}";
    }
}

if (! function_exists('baht_text')) {
    function baht_text($number)
    {
        $number                   = number_format($number, 2, '.', '');
        list($integer, $fraction) = explode('.', $number);

        $txtnum1 = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $txtnum2 = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];

        $convert       = '';
        $number_length = strlen($integer);

        for ($i = 0; $i < $number_length; $i++) {
            $n = (int) $integer[$i];
            if ($n != 0) {
                if ($i == ($number_length - 1) && $n == 1) {
                    $convert .= 'เอ็ด';
                } elseif ($i == ($number_length - 2) && $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($number_length - 2) && $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$number_length - $i - 1];
            }
        }
        $convert .= 'บาท';
        if ($fraction == '00') {
            $convert .= 'ถ้วน';
        } else {
            $number_length = strlen($fraction);
            for ($i = 0; $i < $number_length; $i++) {
                $n = (int) $fraction[$i];
                if ($n != 0) {
                    if ($i == ($number_length - 1) && $n == 1) {
                        $convert .= 'เอ็ด';
                    } elseif ($i == ($number_length - 2) && $n == 2) {
                        $convert .= 'ยี่';
                    } elseif ($i == ($number_length - 2) && $n == 1) {
                        $convert .= '';
                    } else {
                        $convert .= $txtnum1[$n];
                    }
                    $convert .= $txtnum2[$number_length - $i - 1];
                }
            }
            $convert .= 'สตางค์';
        }
        return $convert;
    }
}
