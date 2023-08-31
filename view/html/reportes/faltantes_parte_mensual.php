<?php
    include_once("../../../model/model.php");
    $md=new ProgramModel;
    require_once('../../../TCPDF/tcpdf.php');
    class MyPDF extends TCPDF {
        public function Footer() {
            $currentPage = $this->getAliasNumPage();
            $totalPages = $this->getAliasNbPages();
            $format = 'Página ' . $currentPage . ' de ' . $totalPages;
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, $format, 0, 0, 'C');
        }
    }
    $pdf = new MyPDF('T', 'mm', 'Letter', true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetCreator('Eliud Abraham Español Astudillo');
    $pdf->SetAuthor('Eliud Abraham Español Astudillo');
    $pdf->SetTitle('Reporte de Docentes');
    $pdf->SetSubject('Reporte');
    $pdf->SetKeywords('PDF, TCPDF, PHP');
    $pdf->SetFont('helvetica', 12);
    $lineWidth = 0.2;
    $lineColor = array(0, 0, 0);
    $pdf->SetLineStyle(array('width' => $lineWidth, 'color' => $lineColor));
    $pdf->AddPage();
    $pdf->SetFont('helvetica','B', 17);    
    $pdf->Cell(190, 12,'CENTROS FALTANTES POR ENTREGAR PARTE MENSUAL', 0, 1, 'C');
    $mes=$_POST['mes'];
    $anno=$_POST['anio'];
    $pdf->Cell(190, 7,"PERIODO: ".$anno." -- ".$mes, 0, 1, 'C');
    $pdf->ln();
    $sql="SELECT * FROM centro";
    $centros=$md->capturar($sql);
    $pdf->SetFont('helvetica','B', 12);
    $nc=0;
    foreach ($centros as $indice=>$centro){
        $sql="SELECT id FROM parte_mensual WHERE anno=$anno AND mes='$mes' AND id_centro=".$centro['id_centro'];
        $contar=$md->contar($sql);
        $mcen=print_r($centro,true);
        if($contar==0){
            $nc++;
            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(35, 7,'Codigo Centro: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(30, 7,$centro['Codigo_centro'], 0, 0, 'L');
            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(30, 7,'Nombre: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(95, 7,$centro['Nombre']." Centro:".$nc, 0, 1, 'L');

            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(35, 7,'Municipio: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(30, 7,$centro['Municipio'], 0, 0, 'L');
            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(30, 7,'Direccion: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(95, 7,$centro['Direccion'], 0, 1, 'L');

            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(35, 7,'Tipo: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(30, 7,$centro['Tipo_centro'], 0, 0, 'L');

            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(30, 7,'Telefono: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(35, 7,$centro['Telefono'],0 , 1, 'L');

            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(30, 7,'Estatua: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(30, 7,$centro['estatus'], 0, 1, 'L');
            $pdf->ln();
            $pdf->SetDrawColor(9, 0, 0);
            $pdf->SetLineWidth(1);
            $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());

        }
    }
    $pdf->Output('mi_pdf.pdf', 'I');

