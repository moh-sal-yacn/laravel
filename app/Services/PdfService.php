<?php

namespace App\Services;

use Mpdf\Mpdf;

class PdfService
{
    public static function render(string $html, string $filename = 'document.pdf')
    {
        // ═══════ مجلدات ═══════
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fontDir = storage_path('fonts');

        // ═══════ إعدادات mpdf (مع دعم العربية الكامل) ═══════
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            // ─── الأساسيات ───
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'tempDir'           => $tempDir,

            // ─── الخطوط ───
            'fontDir'           => array_merge($fontDirs, [$fontDir]),
            'fontdata'          => $fontData + [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,   // ← تفعيل OpenType Layout (مهم للعربية!)
                    'useKashida' => 75, // ← تحسين ربط الحروف العربية
                ],
            ],
            'default_font'      => 'cairo',

            // ─── دعم العربية ───
            'autoScriptToLang'  => true,  // ← كشف السكربت تلقائياً
            'autoLangToFont'    => true,  // ← ربط الخط تلقائياً
            'autoArabic'        => true,  // ← دعم العربية التلقائي (مهم جداً!)

            // ─── الاتجاه ───
            'directionality'    => 'rtl',

            // ─── الهوامش ───
            'margin_top'        => 15,
            'margin_bottom'     => 15,
            'margin_left'       => 15,
            'margin_right'      => 15,
            'margin_header'     => 5,
            'margin_footer'     => 5,
        ]);

        // ═══════ تحميل HTML ═══════
        $mpdf->WriteHTML($html);

        // ═══════ إرجاع الملف ═══════
        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}