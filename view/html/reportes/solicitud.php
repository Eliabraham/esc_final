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
    $pdf->SetTitle('Reporte de Solicitudes');
    $pdf->SetSubject('Reporte');
    $pdf->SetKeywords('PDF, TCPDF, PHP');
    $pdf->SetFont('helvetica', 12);
    $lineWidth = 0.2;
    $lineColor = array(0, 0, 0);
    $pdf->SetLineStyle(array('width' => $lineWidth, 'color' => $lineColor));

    foreach ($_POST['lista_reporte'] as $indice=>$opcion){
        $pdf->AddPage();
        $sql="SELECT soli.*,soli.id as consecutivo, cent.*, oper.*, asig.*, doc.*
        FROM solicitudes AS soli
        LEFT JOIN centro AS cent ON cent.id_centro = soli.id_centro
        LEFT JOIN operaciones AS oper ON oper.id = soli.id_tipo_solicitud
        LEFT JOIN designaciones AS asig ON  asig.id = soli.id_solicitante
        LEFT JOIN docente AS doc ON doc.id = asig.id_docente
        WHERE soli.id = ".$opcion;

        $capturar=$md->capturar($sql);
        $pdf->SetFont('helvetica','B', 13);
        $nreporte=' (Solicitud:'.($indice+1).' de: '.count($_POST['lista_reporte']).")";
        $pdf->Cell(190, 12,'DATOS DEL CENTRO EDUCATIVO'.$nreporte, 0, 1, 'C');
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
//______________________________________________________________________________________________________________
        $pdf->SetFont('helvetica','B', 13);    
        $pdf->Cell(190, 12,'SOLICITANTE', 0, 1, 'C');
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(25, 7,'Nombre: ', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(165, 7, $capturar[0]['Nombre1']." ".$capturar[0]['Nombre2']." ".$capturar[0]['Apellido1']." ".$capturar[0]['Apellido2'], 0, 1, 'L');
        
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(25, 7,'Identidad: ', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$capturar[0]['Identidad'] , 0, 0, 'L');
        $pdf->Cell(115, 7,$capturar[0]['titulo'], 0, 1, 'L');

        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(25, 7,'Contacto: ', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$capturar[0]['Telefono'] , 0, 0, 'L');
        $pdf->Cell(115, 7,$capturar[0]['Correo'], 0, 1, 'L');
//________________________________________________________________________________________________________________
        $pdf->SetFont('helvetica','B', 12);
        $pdf->Cell(25, 7,'Cargo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica','', 12);
        $pdf->Cell(50, 7,$capturar[0]['puesto'] , 0, 0, 'L');
        $pdf->Cell(115, 7,"Condicion: ".$capturar[0]['condicion'], 0, 1, 'L');
        $pdf->Cell(25, 7,"Estado" , 0, 0, 'L');
        $pdf->Cell(50, 7,$capturar[0]['estatus'] , 0, 0, 'L');
        $pdf->Cell(115, 7,"Fecha de Asignación: ".$capturar[0]['fasignacion'], 0, 1, 'L');
//_______________________________________________________________________________________________________________
        $pdf->SetFont('helvetica','B', 13);    
        $pdf->Cell(190, 12,'SOLICITUD: '.$capturar[0]['consecutivo'], 0, 1, 'C');
        $pdf->SetFont('helvetica','', 12);    
        $pdf->Cell(40, 7,strtoupper($capturar[0]['fecha_solicitud']), 0, 0, 'L');
        $pdf->Cell(150, 7,strtoupper($capturar[0]['nombre_proceso']), 0, 1, 'L');
        $pdf->Cell(40, 7,"Motivos: ", 0, 0, 'L');
        $pdf->multiCell(150, 7,$capturar[0]['causa'], 0, 'L');
        $pdf->Cell(40, 7,"Etapa Actual: ", 0, 0, 'L');
        $pdf->Cell(150, 7,$capturar[0]['status'], 0, 1, 'L');
        if($capturar[0]['status']!="recepcion"){
            $pdf->Cell(40, 7,"Resolucion:", 0, 0, 'L');
            $pdf->multiCell(150, 7,$capturar[0]['resolucion'], 0, 'L');
        }
        $sql="SELECT * FROM detalle_solicitud WHERE id_solicitud = ".$capturar[0]['consecutivo'];
        $detalles=$md->capturar($sql);
        for($i=0;$i<count($detalles);$i++){
            $pdf->SetFont('helvetica','B', 12);
            $pdf->Cell(190, 7,$detalles[$i]["campo"], 0, 1, 'L');
            $pdf->SetFont('helvetica','', 12);
            $pdf->Cell(190, 7,str_replace('_', ' ',$detalles[$i]['valor']), 0, 1, 'L');
        }
        $sql="SELECT nt.*, asi.puesto, concat(doc.Nombre1,' ',doc.Nombre2,' ',doc.Apellido1,' ',doc.Apellido2) AS nombre_completo, doc.Telefono, doc.Correo 
        FROM observaciones_solicitudes as nt
        LEFT JOIN designaciones AS asi ON asi.id=nt.id_autor
        LEFT JOIN docente AS doc ON doc.id=asi.id_docente
        WHERE nt.id_solicitud=".$capturar[0]['consecutivo'];
        $notas=$md->capturar($sql);
        if(count($notas)>0){
            $pdf->SetFont('helvetica','B', 13);
            $pdf->Cell(190, 12,'CORRESPONDENCIA DE LA SOLICITUD', 0, 1, 'C');
            for($i=0;$i<count($notas);$i++){
                $pdf->SetFont('helvetica','B', 12);
                //$n=print_r($notas);
                $pdf->MultiCell(190, 5,$notas[$i]["Observacion"], 0, 'L');
                $pdf->SetFont('helvetica','', 8);
                
                $pdf->Cell(190, 4,$notas[$i]['fecha']." ".$notas[$i]['nombre_completo']." ".$notas[$i]['puesto']." ".$notas[$i]['Telefono']." ".$notas[$i]['Correo'],  0, 1, 'L');
                $pdf->ln();
            }
        }
    }
    // Método personalizado para el pie de página

    $pdf->Output('mi_pdf.pdf', 'I');
