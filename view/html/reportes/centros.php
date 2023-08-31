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
$pdf->SetTitle('Reporte de Centros Educativos');
$pdf->SetSubject('Reporte');
$pdf->SetKeywords('PDF, TCPDF, PHP');
$pdf->SetFont('helvetica', 12);
$lineWidth = 0.2;
$lineColor = array(0, 0, 0);
$pdf->SetLineStyle(array('width' => $lineWidth, 'color' => $lineColor));

foreach ($_POST['lista_reporte'] as $indice=>$opcion){
    $nreporte=' (Centro:'.($indice+1).' de: '.count($_POST['lista_reporte']).")";
    $pdf->AddPage();
    $sql="SELECT * FROM centro WHERE id_centro = ".$opcion;
    $capturar=$md->capturar($sql);
    $pdf->SetFont('helvetica','B', 17);    
    $pdf->Cell(190, 12,'Centro Educativo '.$nreporte, 0, 1, 'C');
    $pdf->ln(2);
    $pdf->Image(__DIR__."/../../../".$capturar[0]['logo'], 10, 20, 53, 50);
    $desplazamiento = 68;
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Codigo: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7,$capturar[0]['Codigo_centro'], 0, 1, 'L');

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Nombre: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['Nombre'], 0, 1, 'L');

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Tipo: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['Tipo_centro'], 0, 1, 'L');

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Ubicación: ', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['Direccion'], 0, 1, 'L');

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Municipio:', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['Municipio'], 0, 1, 'L');
    
    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'N° Acuerdo:', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['N_acuerdo'], 0, 1, 'L');

    $pdf->SetX($desplazamiento);
    $pdf->SetFont('helvetica','B', 12);
    $pdf->Cell(30, 7,'Estatus:', 0, 0, 'L');
    $pdf->SetFont('helvetica','', 12);
    $pdf->Cell(72, 7, $capturar[0]['estatus'], 0, 1, 'L');

    $fecha_actual = date('d-m-Y');

    $sql="SELECT desi.id AS designacion, desi.puesto, desi.condicion, desi.estatus, desi.horas, desi.fasignacion, desi.fvencimiento, doc.identidad, CONCAT(doc.Nombre1, ' ', doc.Nombre2, ' ', doc.Apellido1, ' ', doc.Apellido2) AS nombre_completo, doc.Telefono, doc.Correo, doc.Status, doc.fecha_nacimeito, doc.titulo 
    FROM designaciones AS desi 
    LEFT JOIN docente AS doc ON doc.id = desi.id_docente
    WHERE desi.id_centro=$opcion AND desi.fasignacion <= '$fecha_actual' AND desi.fvencimiento >='$fecha_actual'";
    $resp=$md->capturar($sql);
    if(count($resp)>0)
    {
        for ($ii = 0; $ii < count($resp); $ii++) {
            if ($resp[$ii]['puesto'] == "Director") {
                $pdf->ln();
                $mostrar_director = 1;
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Cargo:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['puesto'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Nombre:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['nombre_completo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Telefono:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['Telefono'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Email:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['Correo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Estatus:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['Status'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Titulo:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['titulo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Condición:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['condicion'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(50, 7,'Estado de asignación:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(50, 7,$resp[$ii]['estatus'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(45, 7,'Fecha de Asignacion:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(45, 7,$resp[$ii]['fasignacion'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(50, 7,'Fecha de Retiro:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(50, 7,$resp[$ii]['fvencimiento'], 0, 1, 'L');
                $sql="SELECT * FROM estructura_presupuestaria WHERE id_designacion=".$resp[$ii]['designacion'];
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
                    $pdf->Cell(200, 7,'ESTRUCTURA PRESUPUESTARIA NO ASIGNADA', 1, 1, 'L');
                }
                $pdf->ln();
                $pdf->SetDrawColor(9, 0, 0);
                $pdf->SetLineWidth(1);
                $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());
                break;
            }
        }
        for ($ii = 0; $ii < count($resp); $ii++) {
            if ($resp[$ii]['puesto'] != "Director") {
                $pdf->ln(3);
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Cargo:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['puesto'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Nombre:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['nombre_completo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Telefono:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['Telefono'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Email:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['Correo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Estatus:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['Status'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Titulo:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(70, 7,$resp[$ii]['titulo'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(30, 7,'Condición:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(60, 7,$resp[$ii]['condicion'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(50, 7,'Estado de asignación:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(50, 7,$resp[$ii]['estatus'], 0, 1, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(45, 7,'Fecha de Asignacion:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(45, 7,$resp[$ii]['fasignacion'], 0, 0, 'L');
                $pdf->SetFont('helvetica','B', 12);
                $pdf->Cell(50, 7,'Fecha de Retiro:', 0, 0, 'L');
                $pdf->SetFont('helvetica','', 12);
                $pdf->Cell(50, 7,$resp[$ii]['fvencimiento'], 0, 1, 'L');
                $sql="SELECT * FROM estructura_presupuestaria WHERE id_designacion=".$resp[$ii]['designacion'];
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
                $pdf->SetDrawColor(9, 0, 0);
                $pdf->ln();
                $pdf->SetLineWidth(1);
                $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());                
            }
        } 
    }
    /*else
    {
        $pdf->ln(2);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->cell(190, 10,count($resp), 0,1, 'C');
        $pdf->multiCell(190, 10,$sql, 0, 'C');
        $pdf->multiCell(190, 10,"NO HAY DATOS DE ASIGNACIONES DE DOCENTES EN LA FECHA ACTUAL", 0, 'C');
    }*/
}
// Generar el PDF
$pdf->Output('mi_pdf.pdf', 'I');
