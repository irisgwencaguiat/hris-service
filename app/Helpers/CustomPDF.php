<?php

namespace App\Helpers;

use PDF;
use Log;
use Storage;

class CustomPDF extends PDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'id-a5-bg.png';

        // $bg = Storage::get('public/images/id/id-a5-bg.svg');

        // $frontBg = Storage::get('public/images/id/front-bg.svg');
        // $this->ImageSVG('@' . $frontBg, .1, .13, 0, 0, '', '', '', 0, false);
        // $this->ImageSVG('@' . $bg, 0, 0, 100, 200, '', '', '', false, 300, '', false, false, 0);

        $bg = Storage::get('public/images/id/id-a5-bg.png');
        $$this->Image('@' . $bg, .1, .13, 0, 0, '', '', '', 0, false);

        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}