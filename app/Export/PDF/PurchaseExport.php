<?php

namespace App\Export\PDF;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;


class PurchaseExport extends Fpdf
{

    public object $data;
    public Request $request;

    public function initialize($request, $data)
    {
        $this->AddFont('Montserrat', '', 'montserrat.php');
        $this->AddFont('Montserrat', 'B', 'montserratb.php');
        $this->request = $request;
        $this->data = $data;
        $this->generate();
    }


    function Header()
    {
        $this->SetX(0);
        $this->SetY(10);
        $this->SetFillColor(255);
        $this->SetTextColor(168, 1, 2);
        $this->SetFont('Montserrat', 'B', 15);

        //LOGO E TITULO

        $this->Cell(30, 15, $this->Image(public_path('img/const.png'), $this->GetX() + (60 / 2 - 28), $this->GetY() + (28 / 2 - 14), 18, 0, 'PNG', '/', 1, 33.78), 0, 0, 'C', false);

        $this->SetTextColor(CSPDF::$FG_SECONDARY_TITLE[0], CSPDF::$FG_SECONDARY_TITLE[1], CSPDF::$FG_SECONDARY_TITLE[2]);
        $this->Cell(75, 7, utf8_decode("EXTRATO DE COMPRAS"), 0, 0, 'L', true);
        $this->Ln();
        //SEGUNDA LINHA
        $this->setY(17);

        $this->SetTextColor(50, 50, 50);
        $this->Cell(30, 4, "", 0, 0, 'C', false);
        $this->SetFont('Montserrat', 'B', 8);
        $this->Cell(160, 4, utf8_decode("Shopping da Construção"), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(30, 4, "", 0, 0, 'C', false);
        $this->Cell(23, 4, utf8_decode("Comprador: "), 0, 0, 'L', false);
        $this->SetFont('Montserrat', '', 8);
        foreach ($this->data as $model) {
        }
        $this->Cell(107, 4, utf8_decode($model->user->name), 0, 0, 'L', false);
        $this->Ln();

        $this->Cell(30, 4, "", 0, 0, 'C', false);
        $this->SetFont('Montserrat', 'B', 8);
        $this->Cell(23, 4, utf8_decode("Valor total: "), 0, 0, 'L', false);
        $this->SetFont('Montserrat', '', 8);
        $this->Cell(107, 4, utf8_decode("R$" . number_format($model->amount, 2, ",", ".")), 0, 0, 'L', true);

        $this->Ln();

        $this->Cell(30, 4, "", 0, 0, 'C', false);
        $this->SetFont('Montserrat', 'B', 8);

        $this->Cell(23, 4, utf8_decode("Status: "), 0, 0, 'L', false);
        $this->SetFont('Montserrat', '', 8);
        $this->Cell(107, 4, utf8_decode($model->status), 0, 0, 'L', false);
        $this->Ln();
        $this->Ln();
        $this->Ln();

        $this->SetFillColor(CSPDF::$BG_TOOLBAR[0], CSPDF::$BG_TOOLBAR[1], CSPDF::$BG_TOOLBAR[2]);
        $this->SetTextColor(CSPDF::$FG_SECONDARY_TITLE[0], CSPDF::$FG_SECONDARY_TITLE[1], CSPDF::$FG_SECONDARY_TITLE[2]);
        $this->SetFont('Montserrat', 'B', 7);

        $this->Cell(2, 8, utf8_decode(''), 0, 0, 'R', true);
        $this->Cell(55, 8, utf8_decode('PRODUTO'), 0, 0, 'L', true);
        $this->Cell(30, 8, utf8_decode('QUANTIDADE'), 0, 0, 'L', true);
        $this->Cell(30, 8, utf8_decode('VALOR'), 0, 0, 'L', true);
        $this->Cell(25, 8, utf8_decode('TIPO'), 0, 0, 'L', true);
        $this->Cell(30, 8, utf8_decode('MARCA'), 0, 0, 'L', true);
        $this->Ln(10);
    }

    public function generate()
    {
        $this->AddPage();
        $this->SetFillColor(CSPDF::$BD_HEADER_TABLE[0], CSPDF::$BD_HEADER_TABLE[1], CSPDF::$BD_HEADER_TABLE[2]);
        $this->SetTextColor(CSPDF::$FG_HEADER_TABLE[0], CSPDF::$FG_HEADER_TABLE[1], CSPDF::$FG_HEADER_TABLE[2]);
        $this->SetFont('Montserrat', '', 7);

        foreach ($this->data as $models) {
            foreach ($models->userProduct as $model) {
                $this->Cell(2, 7, utf8_decode(''), 0, 0, 'R', true);
                $this->Cell(55, 7, utf8_decode($model->product->name), 0, 0, 'L', true);
                $this->Cell(30, 7, utf8_decode($model->quantyty), 0, 0, 'L', true);
                $this->Cell(30, 7, utf8_decode("R$" . number_format($model->product->value, 2, ",", ".")), 0, 0, 'L', true);
                $this->Cell(25, 7, utf8_decode($model->product->type), 0, 0, 'L', true);
                $this->Cell(25, 7, utf8_decode($model->product->brand->name), 0, 0, 'L', true);
                $this->Cell(5, 7, utf8_decode(''), 0, 0, 'R', true);
                $this->Ln(9);
            }
        }


        $this->SetAuthor('MATHEUS-SOFTWARE', true);
        $this->SetCreator('MATHEUS-SOFTWARE', true);
        $this->SetTitle("EXTRATO.pdf", true);
        $this->Output('I', "compras.pdf", TRUE);
    }



    function Footer()
    {
        $this->SetY(-15);
        $this->SetFillColor(243, 243, 255);
        $this->SetTextColor(80, 80, 210);
        $this->SetFont('Montserrat', '', 8);
    }
}
