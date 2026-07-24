<?php

namespace App\Services;

use App\Models\Certificate;
use Endroid\QrCode\Builder\Builder;

class CertificateImageService
{
    public function png(Certificate $certificate): string
    {
        $dir = resource_path('certificate');

        $im = imagecreatefrompng($dir.'/base.png');
        $W = imagesx($im);

        $navy = imagecolorallocate($im, 26, 42, 112);
        $cyan = imagecolorallocate($im, 23, 150, 190);
        $gray = imagecolorallocate($im, 70, 70, 80);
        $idc  = imagecolorallocate($im, 85, 85, 85);

        $fName = $dir.'/DejaVuSerif-Bold.ttf';
        $fPara = $dir.'/DejaVuSerif.ttf';
        $fId   = $dir.'/DejaVuSans.ttf';

        $name = $certificate->full_name;
        $certid = $certificate->certificate_number;

        $this->centerBaseline($im, $name, 672, 66, $fName, $navy, $W / 2);

        $sz = 25;
        $s1 = 'Throughout the internship, ';
        $s2 = $name;
        $s3 = ' consistently';
        $w1 = $this->textWidth($sz, $fPara, $s1);
        $w2 = $this->textWidth($sz, $fPara, $s2);
        $w3 = $this->textWidth($sz, $fPara, $s3);
        $x = $W / 2 - ($w1 + $w2 + $w3) / 2;
        $base = 972;
        imagettftext($im, $sz, 0, (int) $x, $base, $gray, $fPara, $s1);
        $x += $w1;
        imagettftext($im, $sz, 0, (int) $x, $base, $cyan, $fPara, $s2);
        $x += $w2;
        imagettftext($im, $sz, 0, (int) $x, $base, $gray, $fPara, $s3);

        $url = route('verify.form', ['certificate_number' => $certid]);
        $qrPng = (new Builder())->build(data: $url, size: 200, margin: 0)->getString();
        $qr = imagecreatefromstring($qrPng);
        imagecopyresampled($im, $qr, (int) (1525 - 100), 1338, 0, 0, 200, 200, imagesx($qr), imagesy($qr));
        imagedestroy($qr);

        $this->centerBaseline($im, "Certificate ID: {$certid}", 1575, 19, $fId, $idc, 1525);

        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        imagedestroy($im);

        return $data;
    }

    private function textWidth(int $size, string $font, string $text): float
    {
        $bb = imagettfbbox($size, 0, $font, $text);

        return $bb[2] - $bb[0];
    }

    private function centerBaseline($im, string $text, int $baseY, int $size, string $font, int $color, float $cx): void
    {
        $w = $this->textWidth($size, $font, $text);
        imagettftext($im, $size, 0, (int) ($cx - $w / 2), $baseY, $color, $font, $text);
    }
}
