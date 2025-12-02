<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Apuestas de Murcia</title>
</head>
<body>

<?php

// just require TCPDF instead of FPDF
require_once('tcpdf/tcpdf.php');
require_once('fpdi/fpdi.php');
require_once('tcpdf/config/lang/eng.php');
require_once('database.php');
require_once('switch_visitas_checklist.php');

if(isset($promos) && $promos == 'promos'){
	
	$sql = $conn->prepare($query);
	$sql->execute();
	$promos = $sql->fetchAll();
	
	$hoy = date("d/m/Y H:i:s");
	
	$pdf = new FPDI();
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, 5,5);
	$pdf->SetAutoPageBreak(true, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->AddPage('P');
	
	$pdf->SetFont('times', '', 8);
		
	$pdf->SetXY('0', $pdf->GetY());
	$pdf->Write($h=0, $hoy, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	if (strpos($query, 'aio_clientes_promo') !== false) {

		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, "Clientes promo VIP", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, ' ', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
		$pdf->Cell(45,5,'SALON',1,0,'C',0);   
		$pdf->Cell(40,5,'NOMBRE',1,0,'C',0);
		$pdf->Cell(20,5,'TELEFONO',1,0,'C',0);
		$pdf->Cell(50,5,'EMAIL',1,0,'C',0);  
		$pdf->Cell(20,5,'TICKET',1,1,'C',0);
		
		foreach($promos as $promo){
			$pdf->Cell(45,5,$promo['salon'],1,0,'C',0);   
			$pdf->Cell(40,5,$promo['nombre'],1,0,'C',0);
			$pdf->Cell(20,5,$promo['telefono'],1,0,'C',0);
			$pdf->Cell(50,5,$promo['email'],1,0,'C',0);  
			$pdf->Cell(20,5,$promo['ticket'],1,1,'C',0);
		}

	}else if (strpos($query, 'promo_triples') !== false) {

		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, "Promo triples", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, ' ', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
   
		$pdf->Cell(40,5,'NOMBRE',1,0,'C',0);
		$pdf->Cell(50,5,'EMAIL',1,0,'C',0);  
		$pdf->Cell(50,5,'FECHA',1,1,'C',0);
		
		foreach($promos as $promo){   
			$pdf->Cell(40,5,$promo['nombre'],1,0,'C',0);
			$pdf->Cell(50,5,$promo['email'],1,0,'C',0);  
			$pdf->Cell(50,5,$promo['fecha'],1,1,'C',0);
		}

	}else if (strpos($query, 'promo_canastas') !== false) {

		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, "Promo canastas", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
		$pdf->SetXY('0', $pdf->GetY()+5);
		$pdf->Write($h=0, ' ', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
   
		$pdf->Cell(50,5,'NOMBRE',1,0,'C',0);
		$pdf->Cell(50,5,'EMAIL',1,0,'C',0);  
		$pdf->Cell(50,5,'FECHA',1,0,'C',0);
		$pdf->Cell(20,5,'CANASTAS',1,1,'C',0);
		
		foreach($promos as $promo){
			$pdf->Cell(50,5,$promo['nombre'],1,0,'C',0);   
			$pdf->Cell(50,5,$promo['email'],1,0,'C',0);
			$pdf->Cell(50,5,$promo['fecha'],1,0,'C',0);
			$pdf->Cell(20,5,$promo['canastas'],1,1,'C',0);  
		}

	}
	
	ob_start();

	$file = $img_name = md5(uniqid()) . "_" . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/pdf_promos/'.$file, 'F');
	
	$location = base_url('files/pdf_promos/'.$file);
	
	header("Location: ".$location."");
	
	die();
	
}else if(isset($informe_operadora) && $informe_operadora == "1"){

	$sql = $conn->prepare('select * from informes_salones_operadora WHERE id = :id');
	$sql->execute(array(':id' => $id));
	$visita_info = $sql->fetch();

	$sql = $conn->prepare('select * from salones WHERE id = :id');
	$sql->execute(array(':id' => $visita_info['salon']));
	$salon_info = $sql->fetch();

	$sql = $conn->prepare('select * from operadoras WHERE id = :id');
	$sql->execute(array(':id' => $salon_info['operadora']));
	$operadora_info = $sql->fetch();

	$sql = $conn->prepare('select * from empresas WHERE id = :id');
	$sql->execute(array(':id' => $operadora_info['empresa']));
	$empresa_info = $sql->fetch();

	$sql = $conn->prepare('select * from usuarios WHERE id = :id');
	$sql->execute(array(':id' => $visita_info['creador']));
	$usuario_info = $sql->fetch();

	$pdf = new FPDI();
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, 5,5);
	$pdf->SetAutoPageBreak(false, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pagecount=$pdf->setSourceFile(APPPATH.'/libraries/PDF/plantilla_informes2.pdf');
	$page_number = 1;
			
	$pdf->AddPage('P');
	$tplidx = $pdf->importPage(1);
	$pdf->useTemplate($tplidx);

	/* Titulo */

	$pdf->SetXY(100, 285);
	$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->Rect(18, 22, 173, 8, 'DF', 0, array(216, 0, 57));

	$pdf->SetFont('Helvetica', '', 12);
	$pdf->SetTextColor(255,255,255);

	$pdf->SetXY(74, 23);
	$pdf->Write($h=0, "INFORME DE INSPECCION", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	if(file_exists(APPPATH.'../tickets/files/img/logos_operadoras/'.$empresa_info["id"].'.jpg')){
		$pdf->Image(APPPATH.'../tickets/files/img/logos_operadoras/'.$empresa_info["id"].'.jpg', 32, 36, 30, 25, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
	}else{
		$pdf->Image(APPPATH.'../tickets/files/img/logos_operadoras/logoADM.jpg', 32, 36, 30, 25, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);	
	}

	/* Datos empresa */
	$fecha = explode(' ', $visita_info['fecha']);
	$fecha1 = explode('-', $fecha[0]);
	$fecha2 = explode(':', $fecha[1]);
	$fecha = $fecha1[2]."/".$fecha1[1]."/".$fecha1[0]." ".$fecha[1];
	$fecha_guardar = $fecha1[2]."_".$fecha1[1]."_".$fecha1[0]."_".$fecha2[0]."_".$fecha2[1]."_".$fecha2[2];

	$pdf->SetFont('Helvetica', 'B', 10);
	$pdf->SetTextColor(0,0,0);

	$pdf->SetXY(70, 36);
	$pdf->Write($h=0, "FECHA:", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', '', 10);

	$pdf->SetXY(70, 40);
	$pdf->Write($h=0, $fecha, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', 'B', 10);

	$pdf->SetXY(70, 46);
	$pdf->Write($h=0, "AUTOR:", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', '', 10);

	$pdf->SetXY(70, 50);
	$pdf->Write($h=0, $usuario_info['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', 'B', 10);

	$pdf->SetXY(70, 56);
	$pdf->Write($h=0, "LOCAL:", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', '', 10);

	$pdf->SetXY(70, 60);
	$pdf->Write($h=0, $salon_info['salon'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', 'BU', 10);

	$pdf->SetXY(87, 75);
	$pdf->Write($h=0, "COMPROBACIONES", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$sql = $conn->prepare('select * from informes_salones_operadora_checklist WHERE id_visita = :id');
	$sql->execute(array(':id' => $visita_info['id']));
	if($sql->rowCount() != 0){
		$checklist = $sql->fetch(PDO::FETCH_ASSOC);
		$y_vis = 85;
		$pdf->SetFont('Helvetica', 'B', 10);
		$x_checklist = 18;
		foreach($checklist as $key => $value){
			$checkname = switch_visitas_checklist($key);
			if($checklist[$key] == 1){	
		 		$pdf->SetXY($x_checklist, $y_vis);
				$pdf->Write($h=0, strtoupper($checkname), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				
				if($x_checklist == 18){
					$x_checklist = 110;
				}else{
					$x_checklist = 18;
					$y_vis += 5;
				}						
			}
		}

		if($pdf->GetY() >= 250 || $y_vis >= 250){
			$pdf->AddPage('P');
			$tplidx = $pdf->importPage(1);
			$pdf->useTemplate($tplidx);
			$page_number++;
			$pdf->SetXY(100, 285);
			$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			$y_vis = 25;
			$pdf->SetXY(18, $y_vis);
		}
	}

	$y_vis += 10;
	$pdf->SetFont('Helvetica', 'BU', 10);

	$pdf->SetXY(87, $y_vis);
	$pdf->Write($h=0, "OBSERVACIONES", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$y_vis += 10;
	$pdf->SetFont('Helvetica', 'B', 10);

	$pdf->writeHTML($visita_info['observaciones'], true, false, true, false, '');

	ob_start();

	$file = $salon_info['id'] . "_" . $fecha_guardar . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/pdf_operadoras/'.$file, 'F');

}else if(isset($generar_carnet)){

	$hoy = date("d/m/Y H:i:s");
	
	$pdf = new FPDI();
	$pdf->SetAutoPageBreak(true, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->AddPage('P');

	if(file_exists(APPPATH.'../tickets/files/img/carnet_adm/carnet_apuestas_front.jpg')){
		$pdf->Image(APPPATH.'../tickets/files/img/carnet_adm/carnet_apuestas_front.jpg', 0, 0, 220, 132, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
	}

	if(file_exists(APPPATH.'../tickets/files/img/carnet_adm/carnet_apuestas_back.jpg')){
		$pdf->Image(APPPATH.'../tickets/files/img/carnet_adm/carnet_apuestas_back.jpg', 0, 134, 220, 132, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
	}

	$sql = $conn->prepare('select * from personal WHERE id = :id');
	$sql->execute(array(':id' => $u));
	$personal_info = $sql->fetch();

	if(isset($personal_info['imagen']) && $personal_info['imagen'] != ""){
		if(file_exists(APPPATH.'../tickets/files/img/personal/'.$personal_info['imagen'])){
			$pdf->Image(APPPATH.'../tickets/files/img/personal/'.$personal_info['imagen'], 21, 21, 46, 52, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
		}
	}

	$pdf->SetFont('Helvetica', 'B', 28);
	$pdf->SetTextColor(255,255,255);

	$nombre_completo = explode(" ", $personal_info['nombre']);

	switch(count($nombre_completo)){
		case "1":
			$nombre = substr($nombre_completo[0],0,16);
			$apellido1 = "";
			$apellido2 = "";
			break;
		case "2":
			$nombre = substr($nombre_completo[0],0,16);
			$apellido1 = substr($nombre_completo[1],0,16);
			$apellido2 = "";
			break;
		case "3":
			$nombre = substr($nombre_completo[0],0,16);
			$apellido1 = substr($nombre_completo[1],0,16);
			$apellido2 = substr($nombre_completo[2],0,16);
			break;
		case "4":
			$nombre = substr($nombre_completo[0]." ".$nombre_completo[1],0,16);
			$apellido1 = substr($nombre_completo[2],0,16);
			$apellido2 = substr($nombre_completo[3],0,16);
			break;
		case "5":
			$nombre = substr($nombre_completo[0]." ".$nombre_completo[1]." ".$nombre_completo[2],0,16);
			$apellido1 = substr($nombre_completo[3],0,16);
			$apellido2 = substr($nombre_completo[4],0,16);
			break;
		case "6":
			$nombre = substr($nombre_completo[0]." ".$nombre_completo[1]." ".$nombre_completo[2]." ".$nombre_completo[3],0,16);
			$apellido1 = substr($nombre_completo[4],0,16);
			$apellido2 = substr($nombre_completo[5],0,16);
			break;
		case "7":
			$nombre = substr($nombre_completo[0]." ".$nombre_completo[1]." ".$nombre_completo[2]." ".$nombre_completo[3]." ".$nombre_completo[4],0,16);
			$apellido1 = substr($nombre_completo[5],0,16);
			$apellido2 = substr($nombre_completo[6],0,16);
			break;
	}

	$pdf->SetXY(69, 23);
	$pdf->Write($h=0, $nombre, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY(69, 41);
	$pdf->Write($h=0, $apellido1, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY(69, 59);
	$pdf->Write($h=0, $apellido2, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', 'B', 28);
	$pdf->SetTextColor(255,255,255);

	$pdf->SetXY(69, 74);
	$pdf->Write($h=0, $personal_info['dni'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	if(isset($personal_info['fecha_formacion']) && $personal_info['fecha_formacion'] != ""){
		$fecha = explode("-", $personal_info['fecha_formacion']);
		$fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];
	}else{
		$fecha = "";
	}

	$pdf->SetXY(69, 88);
	$pdf->Write($h=0, $fecha, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	if(isset($personal_info['registro']) && $personal_info['registro'] != ""){
		$registro = $personal_info['registro'];
	}else{
		$registro = "";
	}

	$pdf->SetXY(69, 101);
	$pdf->Write($h=0, $registro, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$file = $u . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/carnets_adm/carnet_'.$file, 'F');

    $filepath = "https://atc.apuestasdemurcia.es/tickets/files/carnets_adm/carnet_".$file;

    $location = base_url('../tickets/files/carnets_adm/carnet_'.$file);

	header("Location: ".$location."");
	
	die();

}else{

	/* Crear PDF Supervisoras */

	$sql = $conn->prepare('select * from empresas WHERE id = :id');
	$sql->execute(array(':id' => $empresa));
	$empresa_info = $sql->fetch();

	$sql = $conn->prepare('select * from usuarios WHERE id = :id');
	$sql->execute(array(':id' => $usuario));
	$usuario_info = $sql->fetch();

	$pdf = new FPDI();
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, 5,5);
	$pdf->SetAutoPageBreak(false, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pagecount=$pdf->setSourceFile(APPPATH.'/libraries/PDF/plantilla_informes2.pdf');
	$page_number = 1;
			
	$pdf->AddPage('P');
	$tplidx = $pdf->importPage(1);
	$pdf->useTemplate($tplidx);

	/* Titulo */

	$pdf->SetXY(100, 285);
	$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->Rect(18, 22, 173, 8, 'DF', 0, array(216, 0, 57));

	$pdf->SetFont('Helvetica', '', 12);
	$pdf->SetTextColor(255,255,255);

	$pdf->SetXY(74, 23);
	$pdf->Write($h=0, "INFORME DE INSPECCION ADM ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	if(file_exists(APPPATH.'../tickets/files/img/logos_operadoras/'.$empresa.'.jpg')){
		$pdf->Image(APPPATH.'../tickets/files/img/logos_operadoras/'.$empresa.'.jpg', 32, 36, 30, 25, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
	}else{
		$pdf->Image(APPPATH.'../tickets/files/img/logos_operadoras/logoADM.jpg', 32, 36, 30, 25, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);	
	}

	/* Datos empresa */

	$pdf->SetFont('Helvetica', 'B', 10);
	$pdf->SetTextColor(0,0,0);

	$pdf->SetXY(70, 36);
	$pdf->Write($h=0, "OPERADORA:", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', '', 10);

	$pdf->SetXY(70, 40);
	$pdf->Write($h=0, $empresa_info['razon_social'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', 'B', 10);

	$pdf->SetXY(70, 46);
	$pdf->Write($h=0, "FECHA:", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetFont('Helvetica', '', 10);

	$pdf->SetXY(70, 50);
	$pdf->Write($h=0, date("d/m/Y"), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	/* Tabla visitas */

	if(count($visitas) > 0){

		$pdf->SetFont('Helvetica', 'BU', 10);

		$pdf->SetXY(18, 70);
		$pdf->Cell(55, 6, "SALÓN", 1, 0, 'C', 0);

		$pdf->SetXY(73, 70);
		$pdf->Cell(40, 6, "FECHA VISITA", 1, 0, 'C', 0);

		$pdf->SetXY(113, 70);
		$pdf->Cell(78, 6, "PERSONAL", 1, 0, 'C', 0);

		$pdf->SetFont('Helvetica', '', 10);

		$y_cell = 76;

		for($i = 0; $i < count($visitas); $i++){

			if($pdf->GetY() >= 250){
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_cell = 25;
				$pdf->SetXY(18, $y_cell);
			}

			$sql = $conn->prepare('select * from visitas WHERE id = :id');
			$sql->execute(array(':id' => $visitas[$i]));
			$visita = $sql->fetch();

			$sql = $conn->prepare('select * from salones WHERE id = :id');
			$sql->execute(array(':id' => $visita['salon']));
			$salon = $sql->fetch();

			$pdf->SetXY(18, $y_cell);
			$pdf->Cell(55, 6, $salon['salon'], 1, 0, 'L', 0);

			$fecha1 = explode(" ", $visita['fecha']);
			$fecha2 = explode("-", $fecha1[0]);
			$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

			$pdf->SetXY(73, $y_cell);
			$pdf->Cell(40, 6, $fecha, 1, 0, 'C', 0);

			if(isset($visita['personal1']) && $visita['personal1'] != '' && $visita['personal1'] != 0){

				$sql = $conn->prepare('select * from personal WHERE id = :id');
				$sql->execute(array(':id' => $visita['personal1']));
				$personal1 = $sql->fetch();

				if(strlen($personal1['nombre']) > 35){
					$personal1_primero = '';
					$personal1_array = explode(" ", $personal1['nombre']);
					for($z = 0; $z < 4; $z++){
						$personal1_primero .= " ".$personal1_array[$z];
					}
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal1_primero, 1, 0, 'L', 0);
					$personal1_primero = '';
					for($z = 4; $z < count($personal1_array); $z++){
						$personal1_primero .= " ".$personal1_array[$z];
					}
					$y_cell += 6;
					$pdf->SetXY(18, $y_cell);
					$pdf->Cell(55, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(73, $y_cell);
					$pdf->Cell(40, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal1_primero, 1, 0, 'L', 0);
				}else{
					$pdf->SetXY(18, $y_cell);
					$pdf->Cell(55, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(73, $y_cell);
					$pdf->Cell(40, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal1['nombre'], 1, 0, 'L', 0);
				}
			}else{				
				$pdf->SetXY(113, $y_cell);
				$pdf->Cell(78, 6, "", 1, 0, 'L', 0);
			}

			if(isset($visita['personal2']) && $visita['personal2'] != ''){

				$y_cell += 6;

				$sql = $conn->prepare('select * from personal WHERE id = :id');
				$sql->execute(array(':id' => $visita['personal2']));
				$personal2 = $sql->fetch();

				if(strlen($personal2['nombre']) > 35){
					$personal2_primero = '';
					$personal2_array = explode(" ", $personal2['nombre']);
					for($z = 0; $z < 4; $z++){
						$personal2_primero .= " ".$personal2_array[$z];
					}
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal2_primero, 1, 0, 'L', 0);
					$personal2_primero = '';
					for($z = 4; $z < count($personal2_array); $z++){
						$personal2_primero .= " ".$personal2_array[$z];
					}
					$y_cell += 6;
					$pdf->SetXY(18, $y_cell);
					$pdf->Cell(55, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(73, $y_cell);
					$pdf->Cell(40, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal2_primero, 1, 0, 'L', 0);
				}else{
					$pdf->SetXY(18, $y_cell);
					$pdf->Cell(55, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(73, $y_cell);
					$pdf->Cell(40, 6, "", 1, 0, 'C', 0);
					$pdf->SetXY(113, $y_cell);
					$pdf->Cell(78, 6, $personal2['nombre'], 1, 0, 'L', 0);
				}
			}
			$y_cell += 6;
		}
	}

	if(count($visitas) > 0){

		$pdf->AddPage('P');
		$tplidx = $pdf->importPage(1);
		$pdf->useTemplate($tplidx);
		$page_number++;
		$pdf->SetXY(100, 285);
		$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		$y_vis = 25;
		$pdf->SetXY(18, $y_vis);

		$contador_visitas = 1;

		for($i = 0; $i < count($visitas); $i++){

			$sql = $conn->prepare('select * from visitas WHERE id = :id');
			$sql->execute(array(':id' => $visitas[$i]));
			$visita = $sql->fetch();

			$sql = $conn->prepare('select * from salones WHERE id = :id');
			$sql->execute(array(':id' => $visita['salon']));
			$salon = $sql->fetch();			

			$string = str_replace("&amp;amp;Aacute;", "Á", $visita['observaciones']);
			$string = str_replace("&amp;amp;Eacute;", "É", $string);
			$string = str_replace("&amp;amp;Iacute;", "Í", $string);
			$string = str_replace("&amp;amp;Oacute;", "Ó", $string);
			$string = str_replace("&amp;amp;Uacute;", "Ú", $string);
			$string = str_replace("&amp;amp;aacute;", "á", $string);
			$string = str_replace("&amp;amp;eacute;", "é", $string);
			$string = str_replace("&amp;amp;iacute;", "í", $string);
			$string = str_replace("&amp;amp;oacute;", "ó", $string);
			$string = str_replace("&amp;amp;uacute;", "ú", $string);
			$string = str_replace("&amp;amp;Ntilde;", "Ñ", $string);
			$string = str_replace("&amp;amp;ntilde;", "ñ", $string);
			$string = str_replace("&amp;amp;nbsp;", " ", $string);
			$string = str_replace("&amp;lt;", "<", $string);
			$string = str_replace("&amp;gt;", ">", $string);
			$string1 = $string;

			$pdf->Rect(18, $y_vis, 173, 7, 'DF', 0, array(230, 184, 183));

			$pdf->SetFont('Helvetica', 'B', 14);			
			$pdf->SetXY(20, $y_vis);
			$pdf->Write($h=0, $salon['salon'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			if($pdf->GetY() >= 250 || $y_vis >= 250){
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);
			}

			if(isset($visita['imagen']) && !empty($visita['imagen']) && $visita['imagen'] != ''){
				$y_vis += 10;
				if(file_exists(APPPATH.'../tickets/files/img/visitas/'.$visita['imagen'])){
					$sql = $conn->prepare('SELECT * FROM visitas_imagenes WHERE visita = :id');
					$sql->execute(array(':id' => $visitas[$i]));					
					$visita_imagenes_total = $sql->rowCount();

					if($visita_imagenes_total > 0){
						$visitas_imagenes_count = $sql->rowCount();
						$visita_imagenes = $sql->fetchAll();
						if($visitas_imagenes_count == 1){
							$x_image = 50;
						}else{
							$x_image = 17;
						}
						$count = 0;
						foreach($visita_imagenes as $visita_imagen){
							$check_y = $y_vis + 50;
							if($check_y >= 250){
								$pdf->AddPage('P');
								$tplidx = $pdf->importPage(1);
								$pdf->useTemplate($tplidx);
								$page_number++;
								$pdf->SetXY(100, 285);
								$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
								$y_vis = 25;
								$pdf->SetXY(18, $y_vis);
							}
							$pdf->Image(APPPATH.'../tickets/files/img/visitas/'.$visita_imagen['imagen'], $x_image, $y_vis, 80, 0, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
							$x_image += 90;
							if($x_image > 110){
								$x_image = 17;
								$y_vis += 200;
								$check_y = $y_vis + 50;
								if($check_y >= 250){
									$pdf->AddPage('P');
									$tplidx = $pdf->importPage(1);
									$pdf->useTemplate($tplidx);
									$page_number++;
									$pdf->SetXY(100, 285);
									$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
									$y_vis = 25;
									$pdf->SetXY(18, $y_vis);
								}
							}
							$count++;
						}
						if($count % 2 == 0){
							$y_vis += 12;
						}else{
							$y_vis += 120;
						}
					}else{
						$check_y = $y_vis + 50;
						if($check_y >= 250){
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
						}
						$pdf->Image(APPPATH.'../tickets/files/img/visitas/'.$visita['imagen'], 50, $y_vis, 80, 0, 'JPG', '', '', true, 150, '', false, false, 1, false, false, false);
						$y_vis += 120;
					}					
				}
			}else{
				$y_vis += 12;
			}

			if($pdf->GetY() >= 250 || $y_vis >= 250){
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);
			}

			$pdf->SetFont('Helvetica', 'B', 10);
			$pdf->SetXY(17, $y_vis);
			$pdf->Write($h=0, "Fecha: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$fecha1 = explode(" ", $visita['fecha']);
			$fecha2 = explode("-", $fecha1[0]);
			$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->SetXY(30, $y_vis);
			$pdf->Write($h=0, $fecha, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', 'B', 10);
			$pdf->SetXY(70, $y_vis);
			$pdf->Write($h=0, "Personal: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			if(isset($visita['personal1']) && $visita['personal1'] != '' && $visita['personal1'] != 0){

				$sql = $conn->prepare('select * from personal WHERE id = :id');
				$sql->execute(array(':id' => $visita['personal1']));
				$personal1 = $sql->fetch();

				$pdf->SetFont('Helvetica', '', 10);
				$pdf->SetXY(87, $y_vis);
				$pdf->Write($h=0, $personal1['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			}
			
			if(isset($visita['personal2']) && $visita['personal2'] != '' && $visita['personal2'] != 0){

				$sql = $conn->prepare('select * from personal WHERE id = :id');
				$sql->execute(array(':id' => $visita['personal2']));
				$personal2 = $sql->fetch();

				$pdf->SetFont('Helvetica', '', 10);
				$pdf->SetXY(87, $pdf->GetY()+2);
				$pdf->Write($h=0, $personal2['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			}
			
			if(isset($visita['creador']) && !empty($visita['creador']) && $visita['creador'] != ''){

				$y_vis = $pdf->GetY()+2;

				$sql = $conn->prepare('select * from usuarios WHERE id = :id');
				$sql->execute(array(':id' => $visita['creador']));
				$creador = $sql->fetch();

				$pdf->SetFont('Helvetica', 'B', 10);
				$pdf->SetXY(17, $y_vis);
				$pdf->Write($h=0, "Supervisora: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$pdf->SetFont('Helvetica', '', 10);
				$pdf->SetXY(40, $y_vis);
				$pdf->Write($h=0, $creador['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			}

			$string1 = str_replace("</p>", "</p><br/>", $string1);
			$string1 = explode("<br/>", $string1);

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->SetMargins(18, 20, 20, true);

			foreach($string1 as $paragraph){
				if(!empty($paragraph) || $paragraph != ""){
					$y_vis = $pdf->GetY()+2;				
					$pdf->SetXY(18, $y_vis);				
					$pdf->writeHTML($paragraph, true, false, true, false, '');
					$y_vis = $pdf->GetY()+5;
				}

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}
			}

			$sql = $conn->prepare('select * from visitas_comentarios_historial WHERE visita = :id');
			$sql->execute(array(':id' => $visitas[$i]));

			if($sql->rowCount() != 0){
				/* Comentarios */
				$pdf->SetFont('Helvetica', 'BU', 10);
				$pdf->SetXY(90, $y_vis);
				$pdf->Write($h=0, "COMENTARIOS", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis = $pdf->GetY()+2;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$visita_comentarios = $sql->fetchAll();

				foreach($visita_comentarios as $visita_comentario){
					$string = str_replace("&amp;amp;Aacute;", "Á", $visita_comentario['comentario']);
					$string = str_replace("&amp;amp;Eacute;", "É", $string);
					$string = str_replace("&amp;amp;Iacute;", "Í", $string);
					$string = str_replace("&amp;amp;Oacute;", "Ó", $string);
					$string = str_replace("&amp;amp;Uacute;", "Ú", $string);
					$string = str_replace("&amp;amp;aacute;", "á", $string);
					$string = str_replace("&amp;amp;eacute;", "é", $string);
					$string = str_replace("&amp;amp;iacute;", "í", $string);
					$string = str_replace("&amp;amp;oacute;", "ó", $string);
					$string = str_replace("&amp;amp;uacute;", "ú", $string);
					$string = str_replace("&amp;amp;Ntilde;", "Ñ", $string);
					$string = str_replace("&amp;amp;ntilde;", "ñ", $string);
					$string = str_replace("&amp;amp;nbsp;", " ", $string);
					$string = str_replace("&amp;lt;", "<", $string);
					$string = str_replace("&amp;gt;", ">", $string);
					$string1 = $string;

					$pdf->SetFont('Helvetica', 'B', 10);
					$pdf->SetXY(17, $y_vis);
					$pdf->Write($h=0, "Fecha: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

					$fecha1 = explode("-", $visita_comentario['fecha']);
					$fecha = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0];

					$pdf->SetFont('Helvetica', '', 10);
					$pdf->SetXY(30, $y_vis);
					$pdf->Write($h=0, $fecha, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

					if($pdf->GetY() >= 250 || $y_vis >= 250){
						$pdf->AddPage('P');
						$tplidx = $pdf->importPage(1);
						$pdf->useTemplate($tplidx);
						$page_number++;
						$pdf->SetXY(100, 285);
						$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
						$y_vis = 25;
						$pdf->SetXY(18, $y_vis);
					}

					if(isset($visita_comentario['creador']) && !empty($visita_comentario['creador']) && $visita_comentario['creador'] != ''){

						$y_vis = $pdf->GetY()+2;

						$sql = $conn->prepare('select * from usuarios WHERE id = :id');
						$sql->execute(array(':id' => $visita_comentario['creador']));
						$creador_comentario = $sql->fetch();

						$pdf->SetFont('Helvetica', 'B', 10);
						$pdf->SetXY(17, $y_vis);
						$pdf->Write($h=0, "Supervisora: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

						$pdf->SetFont('Helvetica', '', 10);
						$pdf->SetXY(40, $y_vis);
						$pdf->Write($h=0, $creador_comentario['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
						}
					}


					$string1 = str_replace("</p>", "</p><br/>", $string1);
					$string1 = explode("<br/>", $string1);

					$pdf->SetFont('Helvetica', '', 10);
					$pdf->SetMargins(18, 20, 20, true);

					foreach($string1 as $paragraph){
						if(!empty($paragraph) || $paragraph != ""){
							$y_vis = $pdf->GetY()+2;				
							$pdf->SetXY(18, $y_vis);				
							$pdf->writeHTML($paragraph, true, false, true, false, '');
							$y_vis = $pdf->GetY()+5;
						}

						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
						}
					}
				}
			}

			// MOBILIARIO

			$pdf->SetXY(93, $y_vis);
			$pdf->SetFont('Helvetica', 'BU', 10);
			$pdf->Write($h=0, "MOBILIARIO", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			if($pdf->GetY() >= 250 || $y_vis >= 250){
				$pdf->SetTextColor(0,0,0);
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);
			}

			$y_vis += 8;

			if($pdf->GetY() >= 250 || $y_vis >= 250){
				$pdf->SetTextColor(0,0,0);
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);
			}

			$pdf->SetFont('Helvetica', 'B', 10);
			$pdf->SetXY(17, $y_vis);
			$pdf->Write($h=0, "Taburetes: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->SetXY(38, $y_vis);
			$pdf->Write($h=0, $visita['taburete'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', 'B', 10);
			$pdf->SetXY(50, $y_vis);
			$pdf->Write($h=0, "Mesas: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->SetXY(64, $y_vis);
			$pdf->Write($h=0, $visita['mesa'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', 'B', 10);
			$pdf->SetXY(80, $y_vis);
			$pdf->Write($h=0, "Tableros: ", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$pdf->SetFont('Helvetica', '', 10);
			$pdf->SetXY(99, $y_vis);
			$pdf->Write($h=0, $visita['tablero'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

			$y_vis += 8;

			if($pdf->GetY() >= 250 || $y_vis >= 250){
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);
			}
			
			$sql = $conn->prepare('select `car_obl1`, `car_obl2`, `fol_lud`, `tvs_cor`, `ver_act`, `cor_est`, `ter_inc`, `com_pro`, `cor_adm`, `per_for`, `inc_apu`, `vin_est`, `bol_pla`, `san_cab`, `dis_may`, `tpv_inc`, `lec_tar`, `señ_gal`, `señ_lot`, `señ_dep`, `señ_otr` from visitas_checklist WHERE id_visita = :id');

			$sql->execute(array(':id' => $visitas[$i]));
			if($sql->rowCount() != 0){

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$visitas_checklist = $sql->fetch(PDO::FETCH_ASSOC);
				$pdf->SetXY(87, $y_vis);
				$pdf->SetFont('Helvetica', 'BU', 10);
				$pdf->Write($h=0, "COMPROBACIONES", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis += 8;
				$pdf->SetTextColor(68,157,68);
				$pdf->SetFont('Helvetica', 'BU', 10);

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$correctas = 0;
				$total_checklist = 0;
				foreach($visitas_checklist as $key => $value){
					if($visitas_checklist[$key] == 1){
						$correctas++;
					}
					$total_checklist++;
				}

				$pdf->SetTextColor(68,157,68);
				$pdf->SetXY(90, $y_vis);
				$pdf->Write($h=0, "CORRECTO ".$correctas."/".$total_checklist, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$pdf->SetTextColor(68,157,68);
				$pdf->SetFont('Helvetica', 'B', 10);
				$z = 0;
				$x_checklist = 18;
				foreach($visitas_checklist as $key => $value){
					$pdf->SetTextColor(68,157,68);
					$checkname = switch_visitas_checklist($key);
					if($visitas_checklist[$key] == 1){
						
						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->SetTextColor(0,0,0);
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
							$pdf->SetTextColor(68,157,68);
						}
						
				 		$pdf->SetXY($x_checklist, $y_vis);
						$pdf->Write($h=0, strtoupper($checkname), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
						
						if($x_checklist == 18){
							$x_checklist = 110;
						}else{
							$x_checklist = 18;
							$y_vis += 5;
						}						
					}
				}

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);					
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$incorrectas = 0;
				$total_checklist = 0;
				foreach($visitas_checklist as $key => $value){
					if($visitas_checklist[$key] == 0){
						$incorrectas++;
					}
					$total_checklist++;
				}

				$pdf->SetFont('Helvetica', 'BU', 10);
				$pdf->SetTextColor(216, 0, 57);
				$pdf->SetXY(90, $y_vis);
				$pdf->Write($h=0, "INCORRECTO ".$incorrectas."/".$total_checklist, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$pdf->SetTextColor(216, 0, 57);
				$pdf->SetFont('Helvetica', 'B', 10);
				$z = 0;
				$x_checklist = 18;
				foreach($visitas_checklist as $key => $value){
					$pdf->SetTextColor(216, 0, 57);
					$checkname = switch_visitas_checklist($key);
					if($visitas_checklist[$key] == 0){

						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->SetTextColor(0,0,0);
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
							$pdf->SetTextColor(216, 0, 57);
						}
						
				 		$pdf->SetXY($x_checklist, $y_vis);
						$pdf->Write($h=0, strtoupper($checkname), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

						if($x_checklist == 18){
							$x_checklist = 110;
						}else{
							$x_checklist = 18;
							$y_vis += 5;
						}
					}
				}
			}

			$sql = $conn->prepare('select `fac_vin`, `piz_int`, `per_uni`, `hil_mus`, `pan_car`, `car_pro`, `tar_adm`, `lim_loc`, `ban_fac`, `aio_est`, `ent_mer` from visitas_checklist WHERE id_visita = :id');

			$sql->execute(array(':id' => $visitas[$i]));
			if($sql->rowCount() != 0){

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$visitas_checklist = $sql->fetch(PDO::FETCH_ASSOC);
				$pdf->SetTextColor(0, 123, 255);
				$pdf->SetFont('Helvetica', 'BU', 10);

				$haciendo_bien = 0;
				$total_checklist = 0;
				foreach($visitas_checklist as $key => $value){
					if($visitas_checklist[$key] == 1){
						$haciendo_bien++;
					}
					$total_checklist++;
				}

				$y_vis += 8;

				$pdf->SetFont('Helvetica', 'BU', 10);
				$pdf->SetTextColor(0, 123, 255);
				$pdf->SetXY(92, $y_vis);
				$pdf->Write($h=0, "OPCIONAL ".$haciendo_bien."/".$total_checklist, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$pdf->SetTextColor(0, 123, 255);
				$pdf->SetFont('Helvetica', 'B', 10);
				$z = 0;
				$x_checklist = 18;
				foreach($visitas_checklist as $key => $value){
					$pdf->SetTextColor(0, 123, 255);
					$checkname = switch_visitas_checklist($key);
					if($visitas_checklist[$key] == 1){

						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->SetTextColor(0,0,0);
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
							$pdf->SetTextColor(0, 123, 255);
						}
						
				 		$pdf->SetXY($x_checklist, $y_vis);
						$pdf->Write($h=0, strtoupper($checkname), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

						if($x_checklist == 18){
							$x_checklist = 110;
						}else{
							$x_checklist = 18;
							$y_vis += 5;
						}
					}
				}

				$correctas = 0;
				$total_checklist = 0;
				foreach($visitas_checklist as $key => $value){
					if($visitas_checklist[$key] == 0){
						$correctas++;
					}
					$total_checklist++;
				}

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);					
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$pdf->SetXY(90, $y_vis);
				$pdf->SetFont('Helvetica', 'BU', 10);
				$pdf->SetTextColor(255,153,0);
				$pdf->Write($h=0, "ACONSEJABLE ".$correctas."/".$total_checklist, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

				$y_vis += 8;

				if($pdf->GetY() >= 250 || $y_vis >= 250){
					$pdf->SetTextColor(0,0,0);				
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

				$pdf->SetFont('Helvetica', 'B', 10);
				$z = 0;
				$x_checklist = 18;
				foreach($visitas_checklist as $key => $value){
					$checkname = switch_visitas_checklist($key);
					if($visitas_checklist[$key] == 0){
						
						if($pdf->GetY() >= 250 || $y_vis >= 250){
							$pdf->SetTextColor(0,0,0);
							$pdf->AddPage('P');
							$tplidx = $pdf->importPage(1);
							$pdf->useTemplate($tplidx);
							$page_number++;
							$pdf->SetXY(100, 285);
							$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
							$y_vis = 25;
							$pdf->SetXY(18, $y_vis);
						}
						
				 		$pdf->SetXY($x_checklist, $y_vis);
				 		$pdf->SetTextColor(255,153,0);
						$pdf->Write($h=0, strtoupper($checkname), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
						
						if($x_checklist == 18){
							$x_checklist = 110;
						}else{
							$x_checklist = 18;
							$y_vis += 5;
						}						
					}
				}

				$pdf->SetTextColor(0,0,0);
				$pdf->AddPage('P');
				$tplidx = $pdf->importPage(1);
				$pdf->useTemplate($tplidx);
				$page_number++;
				$pdf->SetXY(100, 285);
				$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
				$y_vis = 25;
				$pdf->SetXY(18, $y_vis);

			}else{

				if($contador_visitas != count($visitas)){
					$pdf->SetTextColor(0,0,0);
					$pdf->AddPage('P');
					$tplidx = $pdf->importPage(1);
					$pdf->useTemplate($tplidx);
					$page_number++;
					$pdf->SetXY(100, 285);
					$pdf->Write($h=0, $page_number, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
					$y_vis = 25;
					$pdf->SetXY(18, $y_vis);
				}

			}

			$contador_visitas++;
		}
	}
		
	ob_start();

	$file = $img_name = md5(uniqid()) . "_" . $empresa . "_" . date("d_m_Y") . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/pdf/'.$file, 'F');

	$sql = $conn->prepare('INSERT INTO informes_visitas (informe,empresa,fecha,usuario) VALUES (:i,:o,:d,:u)');
	$sql->execute(array(':i' => $file, ':o' => $empresa, ':d' => date("Y-m-d H:i:s"), ':u' => $usuario));
}
?>
</body>
</html>
