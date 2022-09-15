<?php

namespace App\Export\PDF;
use Codedge\Fpdf\Fpdf\Fpdf;
class CSPDF extends Fpdf
{

    /**
     * BG - FILL COLOR
     * FG - TEXT COLOR
     * BD - DRAW COLOR
     */

    static array $BG_TOOLBAR= [222, 235, 252];
    static array $FG_TOOLBAR= [41, 157, 132];
    static array $BD_TOOLBAR= [222, 235, 252];

    static array $FG_PRIMARY_TITLE= [134, 19, 21];
    static array $FG_SECONDARY_TITLE= [0, 61, 147];

    static array $FG_PARAGRAPH= [20,20,20];

    static array $BG_HEADER_TABLE = [240, 240, 240];
    static array $FG_HEADER_TABLE = [70, 78, 95];
    static array $BD_HEADER_TABLE = [243,243,243];

    static string $FAMILY_NAME = "Montserrat";
    static float $DEFAULT_SIZE_TITLE = 15;
    static float $DEFAULT_SIZE_HEADER = 8;
    static float $DEFAULT_SIZE_BODY = 8;

    public static function config()
    {
        define('FPDF_FONTPATH',storage_path()."/fpdf/fonts");
    }

}
