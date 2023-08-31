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
    foreach ($_POST['lista_reporte'] as $indice => $opcion){
        $nreporte=' (Direccion:'.($indice+1).' de: '.count($_POST['lista_reporte']).")";
        $pdf->AddPage();
        $sql="SELECT * FROM direcciones WHERE id = ".$opcion;
        $capturar=$md->capturar($sql);
        $pdf->SetFont('helvetica','B', 17);    
        $pdf->Cell(190, 12,'DIRECCION MUNICIPAL: '.$nreporte, 0, 1, 'C');
        $pdf->ln(2);
        
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(45, 7,'Departamento:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(72, 7,$capturar[0]['departamento'], 0, 1, 'L');

        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(45, 7,'Municipio:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(72, 7,$capturar[0]['municipio'], 0, 1, 'L');

        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(45, 7,'Direccion Geografica:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(72, 7,$capturar[0]['ubicacion'], 0, 1, 'L');

        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(45, 7,'Email:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(72, 7,$capturar[0]['email'], 0, 1, 'L');

        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(45, 7,'Telefono:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(72, 7,$capturar[0]['telefono'], 0, 1, 'L');
        $pdf->ln(5);
        $sql="SELECT desi.*, doc.* FROM designaciones as desi LEFT JOIN docente AS doc ON doc.id=desi.id_docente WHERE desi.id_direccion = $opcion ORDER BY desi.fasignacion desc";
        $designaciones = $md->capturar($sql);
        foreach ($designaciones as $designacion){
            $pdf->ln();
            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(25, 7,'Director(a):', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(80, 7,$designacion['Nombre1']." ".$designacion['Nombre2']." ".$designacion['Apellido1']." ".$designacion['Apellido2'], 0, 0, 'L'); 
            $pdf->Cell(85, 7,$designacion["titulo"], 0, 1, 'L'); 

            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(25, 7,'Periodo:', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(72, 7,$designacion['fasignacion']." Hasta: ".$designacion['fvencimiento'], 0, 1, 'L'); 

            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(40, 7,$designacion['Telefono'], 0, 0, 'L'); 
            $pdf->Cell(150, 7,$designacion['Correo'], 0, 1, 'L'); 

            $pdf->SetDrawColor(9, 0, 0);
            $pdf->SetLineWidth(1);
            $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());                
        }
    }
    $pdf->Output('mi_pdf.pdf', 'I');