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

foreach ($_POST['lista_reporte'] as $indice=>$opcion){
    
    $nreporte=' (Docente:'.($indice+1).' de: '.count($_POST['lista_reporte']).")";
    $pdf->AddPage();
    $sql="SELECT * FROM docente WHERE id = ".$opcion;
    $capturar=$md->capturar($sql);
    $pdf->SetFont('helvetica','B', 17);    
    $pdf->Cell(190, 12,'Reporte de Docentes'.$nreporte, 0, 1, 'C');
    $pdf->ln(2);

    $pdf->Image(__DIR__."/../../../".$capturar[0]['Foto'], 5, 20, 53, 50);
    $desplazamiento = 68;

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(56, 7,'Documento de Identidad: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7,$capturar[0]['Identidad'], 0, 1, 'L');
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(56, 7,'Nombre Completo: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7,$capturar[0]['Nombre1']." ".$capturar[0]['Nombre2']." ".$capturar[0]['Apellido1']." ".$capturar[0]['Apellido2'], 0, 1, 'L');
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(25, 7,'Escalafon ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(31, 7,$capturar[0]['Escalafon'], 0, 0, 'L');    
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(23, 7,'Imprema ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(49, 7,$capturar[0]['Imprema'], 0, 1, 'L');
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(25, 7,'Telefono: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(31, 7,$capturar[0]['Telefono'], 0, 0, 'L');
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(23, 7,'Correo ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(49, 7,$capturar[0]['Correo'], 0, 1, 'L');
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(25, 7,'Estatus ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(31, 7,$capturar[0]['Status'], 0, 0, 'L');
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(23, 7,'sexo:', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(49, 7,$capturar[0]['sexo'], 0, 1, 'L');
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(56, 7,'Fecha de Nacimiento: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $anio = substr($capturar[0]['fecha_nacimeito'], 0, 4);
    $mes = substr($capturar[0]['fecha_nacimeito'], 5, 2);
    $dia = substr($capturar[0]['fecha_nacimeito'], 8, 2);
    $fecha_actual = date('Y-m-d');
    $diff = date_diff(date_create("$anio-$mes-$dia"), date_create($fecha_actual));
    $edad = $diff->y;
    $pdf->Cell(72, 7,$capturar[0]['fecha_nacimeito']." (".$edad." años)", 0, 1, 'L');    
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(56, 7,'Titulo ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7,$capturar[0]['titulo'], 0, 1, 'L');

    $sql ="SELECT * FROM designaciones WHERE id_docente=".$opcion;
    $designaciones=$md->capturar($sql);
    foreach ($designaciones as $designacion){
        $pdf->ln(2);
        $pdf->SetFont('helvetica','B', 12);
        if(!is_null($designacion["id_centro"])){
            $sql="SELECT * FROM centro WHERE id_centro=".$designacion["id_centro"];
            $centro=$md->capturar($sql);
            $pdf->Cell(20, 7,'Centro:', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(160, 7,$centro[0]['Codigo_centro']." - ".$centro[0]['Nombre']." - ".$centro[0]['Tipo_centro'], 0, 1, 'L');
            $pdf->Cell(180, 7,"Municipio: ".$centro[0]['Municipio']." - ".$centro[0]['Direccion']." Telefono: ".$centro[0]['Telefono'], 0, 1, 'L');
        }
        if(!is_null($designacion["id_direccion"])){
            $sql="SELECT * FROM direcciones WHERE id=".$designacion["id_direccion"];
            $direccion=$md->capturar($sql);
            $pdf->Cell(20, 7,'Direccion: ', 0, 0, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(160, 7," ".$direccion[0]['departamento']." - ".$direccion[0]['municipio'], 0, 1, 'L');
            $pdf->Cell(180, 7," ".$direccion[0]['email']." - ".$direccion[0]['telefono'], 0, 1, 'L');
            
        }
        $pdf->Cell(50, 7,'Cargo:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$designacion['puesto'], 0, 0, 'L');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(40, 7,'Condicion:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(40, 7,$designacion['condicion'], 0, 1, 'L');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(50, 7,'Estatus:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$designacion['estatus'], 0, 0, 'L');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(40, 7,'Horas:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(40, 7,$designacion['horas'], 0, 1, 'L');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(50, 7,'Fecha de Asignacion:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$designacion['fasignacion'], 0, 0, 'L');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(40, 7,'Fecha de Retiro:', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(40, 7,$designacion['fvencimiento'], 0, 1, 'L');
        $sql="SELECT * FROM estructura_presupuestaria WHERE id_designacion=".$designacion['id'];
        $est=$md->capturar($sql);
        if(count($est)>0){
            $pdf->SetDrawColor(170, 170, 170); // Color gris en RGB
            $pdf->SetLineWidth(0.1); //
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(30, 7,'Dependencia', 1, 0, 'L');
            $pdf->Cell(40, 7,'Departamento', 1, 0, 'L');
            $pdf->Cell(30, 7,'Municipio', 1, 0, 'L');
            $pdf->Cell(30, 7,'Centro', 1, 0, 'L');
            $pdf->Cell(30, 7,'Plaza', 1, 0, 'L');
            $pdf->Cell(30, 7,'Horas', 1, 1, 'L');
            for($iii=0;$iii<count($est);$iii++){
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(30, 7,$est[$iii]['dependencia'], 1, 0, 'L');
                $pdf->Cell(40, 7,$est[$iii]['departamento'], 1, 0, 'L');
                $pdf->Cell(30, 7,$est[$iii]['municipio'], 1, 0, 'L');
                $pdf->Cell(30, 7,$est[$iii]['cod_centro'], 1, 0, 'L');
                $pdf->Cell(30, 7,$est[$iii]['cod_plaza'], 1, 0, 'L');
                $pdf->Cell(30, 7,$est[$iii]['horas'], 1, 1, 'L');
            }
        }else{
            $pdf->Cell(190, 7,'ESTRUCTURA PRESUPUESTARIA NO ASIGNADA', 0, 1, 'C');
        }
        $pdf->ln();
        $pdf->SetDrawColor(9, 0, 0);
        $pdf->SetLineWidth(1);
        $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());
    }    
}
// Generar el PDF
$pdf->Output('mi_pdf.pdf', 'I');
