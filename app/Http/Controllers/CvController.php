<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CvController extends Controller
{
    /**
     * The CV rendered as HTML inside the site layout.
     */
    public function show(): View
    {
        return view('cv', ['html' => $this->html()]);
    }

    /**
     * The raw Markdown source, kept out of search results to avoid duplicate content.
     */
    public function markdown(): Response
    {
        return response($this->source(), 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    /**
     * The CV as a printable PDF, generated from the same Markdown source.
     */
    public function pdf(): Response
    {
        $dompdf = new Dompdf(new Options([
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
        ]));

        $dompdf->loadHtml(view('cv-pdf', [
            'html' => $this->html(),
            'photo' => $this->photoDataUri(),
        ])->render(), 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $filename = 'cesar-garcia-cv-'.app()->getLocale().'.pdf';

        return response((string) $dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    private function html(): string
    {
        return Str::markdown($this->source(), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * The profile photo as an embedded JPEG: dompdf runs without remote access and is
     * safest with JPEG, so the WebP source is cropped to a square and converted here.
     */
    private function photoDataUri(int $size = 300): ?string
    {
        $source = @imagecreatefromwebp(resource_path('images/profile.webp'));

        if ($source === false) {
            return null;
        }

        $side = min(imagesx($source), imagesy($source));
        $photo = imagecreatetruecolor($size, $size);

        imagecopyresampled(
            $photo, $source, 0, 0,
            intdiv(imagesx($source) - $side, 2), intdiv(imagesy($source) - $side, 2),
            $size, $size, $side, $side,
        );

        ob_start();
        imagejpeg($photo, null, 85);

        return 'data:image/jpeg;base64,'.base64_encode((string) ob_get_clean());
    }

    private function source(): string
    {
        return file_get_contents(resource_path('cv/'.app()->getLocale().'.md'));
    }
}
