<?php

namespace App\Services;

use App\Models\Certificate;
use Endroid\QrCode\Builder\Builder;

class CertificateImageService
{
    public const DEFAULT_START = '2026-06-01';
    public const DEFAULT_END   = '2026-07-31';

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

        $start = $certificate->start_date
            ? $certificate->start_date->format('F d')
            : \Carbon\Carbon::parse(self::DEFAULT_START)->format('F d');
        $end = $certificate->end_date
            ? $certificate->end_date->format('F d, Y')
            : \Carbon\Carbon::parse(self::DEFAULT_END)->format('F d, Y');

        $this->centerBaseline($im, $name, 672, 66, $fName, $navy, $W / 2);

        $szd = 25;
        $d1 = 'at NovaStack Hub from ';
        $d3 = ' to ';
        $w1 = $this->textWidth($szd, $fPara, $d1);
        $w2 = $this->textWidth($szd, $fPara, $start);
        $w3 = $this->textWidth($szd, $fPara, $d3);
        $w4 = $this->textWidth($szd, $fPara, $end);
        $xd = $W / 2 - ($w1 + $w2 + $w3 + $w4) / 2;
        imagettftext($im, $szd, 0, (int) $xd, 842, $navy, $fPara, $d1);
        $xd += $w1;
        imagettftext($im, $szd, 0, (int) $xd, 842, $cyan, $fPara, $start);
        $xd += $w2;
        imagettftext($im, $szd, 0, (int) $xd, 842, $navy, $fPara, $d3);
        $xd += $w3;
        imagettftext($im, $szd, 0, (int) $xd, 842, $cyan, $fPara, $end);

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

    public function pdf(Certificate $certificate): string
    {
        $png = $this->png($certificate);
        $im = imagecreatefromstring($png);
        $w = imagesx($im);
        $h = imagesy($im);
        ob_start();
        imagejpeg($im, null, 92);
        $jpg = ob_get_clean();
        imagedestroy($im);

        $pw = 841.89;
        $ph = 595.28;
        $scale = min($pw / $w, $ph / $h);
        $iw = $w * $scale;
        $ih = $h * $scale;
        $ox = ($pw - $iw) / 2;
        $oy = ($ph - $ih) / 2;
        $content = sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /Im0 Do Q', $iw, $ih, $ox, $oy);

        $objs = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            3 => "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$pw} {$ph}] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>",
            4 => "<< /Type /XObject /Subtype /Image /Width {$w} /Height {$h} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ".strlen($jpg)." >>\nstream\n".$jpg."\nendstream",
            5 => '<< /Length '.strlen($content)." >>\nstream\n".$content."\nendstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objs as $n => $body) {
            $offsets[$n] = strlen($pdf);
            $pdf .= "{$n} 0 obj\n{$body}\nendobj\n";
        }
        $xref = strlen($pdf);
        $count = count($objs) + 1;
        $pdf .= "xref\n0 {$count}\n0000000000 65535 f \n";
        foreach ($objs as $n => $_) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$n]);
        }
        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
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
