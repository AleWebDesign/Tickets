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

if(isset($reca)){

	$hoy = date("d/m/Y H:i:s");
	
	$pdf = new FPDI();
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, 5,5);
	$pdf->SetAutoPageBreak(true, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->setSourceFile(APPPATH.'/libraries/PDF_recaudaciones/recaudaciones1.pdf');
	
	$pdf->AddPage('P');
	$tplidx = $pdf->importPage(1);
	$pdf->useTemplate($tplidx);

	$pdf->SetFont('times', '', 9);
	
	$sql = $conn->prepare('SELECT * FROM salones WHERE id = :id');
	$sql->execute(array(':id' => $reca->salon));
	$salon = $sql->fetch();
	
	$sql = $conn->prepare('SELECT * FROM usuarios WHERE id = :id');
	$sql->execute(array(':id' => $reca->recaudador));
	$usuario = $sql->fetch();
	
	$pdf->SetXY('50', '9');
	$pdf->Write($h=0, $salon['salon'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('60', '14');
	$pdf->Write($h=0, $usuario['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('25', '18');
	$pdf->Write($h=0, $hoy, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$sql = $conn->prepare('SELECT * FROM recaudaciones_maquinas WHERE recaudacion = :id');
	$sql->execute(array(':id' => $reca->id));
	$maquinas = $sql->fetchAll();
	
	$count = 0;	
	foreach($maquinas as $maquina){
		
		$sql = $conn->prepare('SELECT * FROM maquinas WHERE id = :id');
		$sql->execute(array(':id' => $maquina['maquina']));
		$nombre = $sql->fetch();
		
		if($count != 0 && $count % 4 == 0){
			$count = 0;
			
			$pdf->AddPage('P');
			$tplidx = $pdf->importPage(1);
			$pdf->useTemplate($tplidx);
			
			$pdf->SetXY('50', '9');
			$pdf->Write($h=0, $salon['salon'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('60', '14');
			$pdf->Write($h=0, $usuario['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('25', '18');
			$pdf->Write($h=0, $hoy, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		}		
		
		if($count == 0){
			
			$pdf->SetXY('32', '35');
			$pdf->Write($h=0, $maquina['t_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '35');
			$pdf->Write($h=0, $maquina['t_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '41');
			$pdf->Write($h=0, $maquina['t_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '41');
			$pdf->Write($h=0, $maquina['t_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '47');
			$pdf->Write($h=0, $maquina['t_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '53');
			$pdf->Write($h=0, $maquina['t_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '53');
			$pdf->Write($h=0, $maquina['t_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '58');
			$pdf->Write($h=0, $maquina['t_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '58');
			$pdf->Write($h=0, $maquina['t_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '64');
			$pdf->Write($h=0, $maquina['t_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '64');
			$pdf->Write($h=0, $maquina['t_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '69');
			$pdf->Write($h=0, $maquina['t_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '69');
			$pdf->Write($h=0, $maquina['t_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '75');
			$pdf->Write($h=0, $maquina['t_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '81');
			$pdf->Write($h=0, $maquina['t_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '81');
			$pdf->Write($h=0, $maquina['t_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '86');
			$pdf->Write($h=0, $maquina['t_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '86');
			$pdf->Write($h=0, $maquina['t_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '92');
			$pdf->Write($h=0, $maquina['t_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '98');
			$pdf->Write($h=0, $maquina['t_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '98');
			$pdf->Write($h=0, $maquina['t_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '104');
			$pdf->Write($h=0, $maquina['t_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '110');
			$pdf->Write($h=0, $maquina['t_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '121');
			$pdf->Write($h=0, $nombre['maquina'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '35');
			$pdf->Write($h=0, $maquina['r_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '35');
			$pdf->Write($h=0, $maquina['r_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '41');
			$pdf->Write($h=0, $maquina['r_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '41');
			$pdf->Write($h=0, $maquina['r_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '47');
			$pdf->Write($h=0, $maquina['r_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '53');
			$pdf->Write($h=0, $maquina['r_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '53');
			$pdf->Write($h=0, $maquina['r_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '58');
			$pdf->Write($h=0, $maquina['r_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '58');
			$pdf->Write($h=0, $maquina['r_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '64');
			$pdf->Write($h=0, $maquina['r_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '64');
			$pdf->Write($h=0, $maquina['r_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '69');
			$pdf->Write($h=0, $maquina['r_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '69');
			$pdf->Write($h=0, $maquina['r_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '75');
			$pdf->Write($h=0, $maquina['r_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '81');
			$pdf->Write($h=0, $maquina['r_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '81');
			$pdf->Write($h=0, $maquina['r_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '86');
			$pdf->Write($h=0, $maquina['r_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '86');
			$pdf->Write($h=0, $maquina['r_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '92');
			$pdf->Write($h=0, $maquina['r_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '98');
			$pdf->Write($h=0, $maquina['r_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '98');
			$pdf->Write($h=0, $maquina['r_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '104');
			$pdf->Write($h=0, $maquina['r_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '110');
			$pdf->Write($h=0, $maquina['r_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '115');
			$pdf->Write($h=0, $maquina['carga'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '121');
			$pdf->Write($h=0, $maquina['neto'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
		}else if($count == 1){
			
			$pdf->SetXY('132', '35');
			$pdf->Write($h=0, $maquina['t_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '35');
			$pdf->Write($h=0, $maquina['t_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '41');
			$pdf->Write($h=0, $maquina['t_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '41');
			$pdf->Write($h=0, $maquina['t_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '47');
			$pdf->Write($h=0, $maquina['t_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '53');
			$pdf->Write($h=0, $maquina['t_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '53');
			$pdf->Write($h=0, $maquina['t_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '58');
			$pdf->Write($h=0, $maquina['t_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '58');
			$pdf->Write($h=0, $maquina['t_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '64');
			$pdf->Write($h=0, $maquina['t_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '64');
			$pdf->Write($h=0, $maquina['t_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '69');
			$pdf->Write($h=0, $maquina['t_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '69');
			$pdf->Write($h=0, $maquina['t_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '75');
			$pdf->Write($h=0, $maquina['t_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '81');
			$pdf->Write($h=0, $maquina['t_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '81');
			$pdf->Write($h=0, $maquina['t_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '86');
			$pdf->Write($h=0, $maquina['t_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '86');
			$pdf->Write($h=0, $maquina['t_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '92');
			$pdf->Write($h=0, $maquina['t_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '98');
			$pdf->Write($h=0, $maquina['t_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '98');
			$pdf->Write($h=0, $maquina['t_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '104');
			$pdf->Write($h=0, $maquina['t_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '110');
			$pdf->Write($h=0, $maquina['t_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '121');
			$pdf->Write($h=0, $nombre['maquina'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '35');
			$pdf->Write($h=0, $maquina['r_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '35');
			$pdf->Write($h=0, $maquina['r_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '41');
			$pdf->Write($h=0, $maquina['r_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '41');
			$pdf->Write($h=0, $maquina['r_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '47');
			$pdf->Write($h=0, $maquina['r_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '53');
			$pdf->Write($h=0, $maquina['r_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '53');
			$pdf->Write($h=0, $maquina['r_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '58');
			$pdf->Write($h=0, $maquina['r_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '58');
			$pdf->Write($h=0, $maquina['r_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '64');
			$pdf->Write($h=0, $maquina['r_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '64');
			$pdf->Write($h=0, $maquina['r_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '69');
			$pdf->Write($h=0, $maquina['r_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '69');
			$pdf->Write($h=0, $maquina['r_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '75');
			$pdf->Write($h=0, $maquina['r_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '81');
			$pdf->Write($h=0, $maquina['r_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '81');
			$pdf->Write($h=0, $maquina['r_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '86');
			$pdf->Write($h=0, $maquina['r_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '86');
			$pdf->Write($h=0, $maquina['r_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '92');
			$pdf->Write($h=0, $maquina['r_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '98');
			$pdf->Write($h=0, $maquina['r_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '98');
			$pdf->Write($h=0, $maquina['r_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '104');
			$pdf->Write($h=0, $maquina['r_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '110');
			$pdf->Write($h=0, $maquina['r_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '115');
			$pdf->Write($h=0, $maquina['carga'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '121');
			$pdf->Write($h=0, $maquina['neto'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
		}else if($count == 2){
			
			$pdf->SetXY('32', '142');
			$pdf->Write($h=0, $maquina['t_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '142');
			$pdf->Write($h=0, $maquina['t_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '148');
			$pdf->Write($h=0, $maquina['t_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '148');
			$pdf->Write($h=0, $maquina['t_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '154');
			$pdf->Write($h=0, $maquina['t_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '160');
			$pdf->Write($h=0, $maquina['t_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '160');
			$pdf->Write($h=0, $maquina['t_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '165');
			$pdf->Write($h=0, $maquina['t_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '165');
			$pdf->Write($h=0, $maquina['t_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '170');
			$pdf->Write($h=0, $maquina['t_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '170');
			$pdf->Write($h=0, $maquina['t_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '176');
			$pdf->Write($h=0, $maquina['t_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '176');
			$pdf->Write($h=0, $maquina['t_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '182');
			$pdf->Write($h=0, $maquina['t_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '188');
			$pdf->Write($h=0, $maquina['t_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '188');
			$pdf->Write($h=0, $maquina['t_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '193');
			$pdf->Write($h=0, $maquina['t_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '193');
			$pdf->Write($h=0, $maquina['t_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '199');
			$pdf->Write($h=0, $maquina['t_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '205');
			$pdf->Write($h=0, $maquina['t_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '205');
			$pdf->Write($h=0, $maquina['t_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '211');
			$pdf->Write($h=0, $maquina['t_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('45', '217');
			$pdf->Write($h=0, $maquina['t_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('32', '228');
			$pdf->Write($h=0, $nombre['maquina'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '142');
			$pdf->Write($h=0, $maquina['r_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '142');
			$pdf->Write($h=0, $maquina['r_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '148');
			$pdf->Write($h=0, $maquina['r_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '148');
			$pdf->Write($h=0, $maquina['r_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '154');
			$pdf->Write($h=0, $maquina['r_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '160');
			$pdf->Write($h=0, $maquina['r_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '160');
			$pdf->Write($h=0, $maquina['r_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '165');
			$pdf->Write($h=0, $maquina['r_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '165');
			$pdf->Write($h=0, $maquina['r_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '170');
			$pdf->Write($h=0, $maquina['r_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '170');
			$pdf->Write($h=0, $maquina['r_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '176');
			$pdf->Write($h=0, $maquina['r_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '176');
			$pdf->Write($h=0, $maquina['r_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '182');
			$pdf->Write($h=0, $maquina['r_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '188');
			$pdf->Write($h=0, $maquina['r_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '188');
			$pdf->Write($h=0, $maquina['r_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '193');
			$pdf->Write($h=0, $maquina['r_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '193');
			$pdf->Write($h=0, $maquina['r_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '199');
			$pdf->Write($h=0, $maquina['r_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('77', '205');
			$pdf->Write($h=0, $maquina['r_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '205');
			$pdf->Write($h=0, $maquina['r_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '211');
			$pdf->Write($h=0, $maquina['r_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '217');
			$pdf->Write($h=0, $maquina['r_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '222');
			$pdf->Write($h=0, $maquina['carga'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('88', '228');
			$pdf->Write($h=0, $maquina['neto'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
		}else if($count == 3){
			
			$pdf->SetXY('132', '142');
			$pdf->Write($h=0, $maquina['t_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '142');
			$pdf->Write($h=0, $maquina['t_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '148');
			$pdf->Write($h=0, $maquina['t_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '148');
			$pdf->Write($h=0, $maquina['t_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '154');
			$pdf->Write($h=0, $maquina['t_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '160');
			$pdf->Write($h=0, $maquina['t_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '160');
			$pdf->Write($h=0, $maquina['t_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '165');
			$pdf->Write($h=0, $maquina['t_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '165');
			$pdf->Write($h=0, $maquina['t_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '170');
			$pdf->Write($h=0, $maquina['t_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '170');
			$pdf->Write($h=0, $maquina['t_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '176');
			$pdf->Write($h=0, $maquina['t_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '176');
			$pdf->Write($h=0, $maquina['t_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '182');
			$pdf->Write($h=0, $maquina['t_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '188');
			$pdf->Write($h=0, $maquina['t_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '188');
			$pdf->Write($h=0, $maquina['t_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '193');
			$pdf->Write($h=0, $maquina['t_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '193');
			$pdf->Write($h=0, $maquina['t_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '199');
			$pdf->Write($h=0, $maquina['t_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '205');
			$pdf->Write($h=0, $maquina['t_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '205');
			$pdf->Write($h=0, $maquina['t_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '211');
			$pdf->Write($h=0, $maquina['t_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('146', '217');
			$pdf->Write($h=0, $maquina['t_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('132', '228');
			$pdf->Write($h=0, $nombre['maquina'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '142');
			$pdf->Write($h=0, $maquina['r_h_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '142');
			$pdf->Write($h=0, $maquina['r_h_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '148');
			$pdf->Write($h=0, $maquina['r_h_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '148');
			$pdf->Write($h=0, $maquina['r_h_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '154');
			$pdf->Write($h=0, $maquina['r_h_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '160');
			$pdf->Write($h=0, $maquina['r_b_u_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '160');
			$pdf->Write($h=0, $maquina['r_b_t_5'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '165');
			$pdf->Write($h=0, $maquina['r_b_u_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '165');
			$pdf->Write($h=0, $maquina['r_b_t_10'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '170');
			$pdf->Write($h=0, $maquina['r_b_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '170');
			$pdf->Write($h=0, $maquina['r_b_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '176');
			$pdf->Write($h=0, $maquina['r_b_u_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '176');
			$pdf->Write($h=0, $maquina['r_b_t_50'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '182');
			$pdf->Write($h=0, $maquina['r_b_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '188');
			$pdf->Write($h=0, $maquina['r_c_u_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '188');
			$pdf->Write($h=0, $maquina['r_c_t_1'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '193');
			$pdf->Write($h=0, $maquina['r_c_u_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '193');
			$pdf->Write($h=0, $maquina['r_c_t_2'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '199');
			$pdf->Write($h=0, $maquina['r_c_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('178', '205');
			$pdf->Write($h=0, $maquina['r_r_u_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '205');
			$pdf->Write($h=0, $maquina['r_r_t_20'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '211');
			$pdf->Write($h=0, $maquina['r_r_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '217');
			$pdf->Write($h=0, $maquina['r_reca_t'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '222');
			$pdf->Write($h=0, $maquina['carga'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
			$pdf->SetXY('189', '228');
			$pdf->Write($h=0, $maquina['neto'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
			
		}		
		
		$count++;
	}
	
	$pdf->setSourceFile(APPPATH.'/libraries/PDF_recaudaciones/recaudaciones3.pdf');
	
	$pdf->AddPage('P');
	$tplidx = $pdf->importPage(1);
	$pdf->useTemplate($tplidx);
	
	$pdf->SetXY('105', '11');
	$pdf->Write($h=0, $reca->reca_ant, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('127', '11');
	$pdf->Write($h=0, $reca->pag_ant, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('150', '11');
	$pdf->Write($h=0, $reca->bal_ant, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('105', '23');
	$pdf->Write($h=0, $reca->pag_caj, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('127', '23');
	$pdf->Write($h=0, $reca->bal, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('150', '23');
	$pdf->Write($h=0, $reca->total, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('127', '35');
	$pdf->Write($h=0, $reca->reca_total."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('127', '48');
	$pdf->Write($h=0, $reca->pagos."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('127', '61');
	$pdf->Write($h=0, $reca->pagos_1."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '74');
	$pdf->Write($h=0, $reca->pagos_2."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '87');
	$pdf->Write($h=0, $reca->pagos_5."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '100');
	$pdf->Write($h=0, $reca->pagos_10."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '112');
	$pdf->Write($h=0, $reca->pagos_20."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '124');
	$pdf->Write($h=0, $reca->pagos_50."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('127', '137');
	$pdf->Write($h=0, $reca->neto."€", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('60', '159');
	$pdf->Write($h=0, $salon['salon'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('67', '164');
	$pdf->Write($h=0, $usuario['nombre'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('65', '169');
	$pdf->Write($h=0, $hoy, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->SetXY('30', '175');
	$pdf->Write($h=0, "Observaciones: ".$reca->comentarios, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('65', '180');
	$pdf->Write($h=0, $reca->reca_total, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('48', '190');
	$pdf->Write($h=0, $reca->pagos, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('55', '199');
	$pdf->Write($h=0, $reca->neto, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('37', '208');
	$pdf->Write($h=0, $salon['poblacion'], $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('64', '208');
	$pdf->Write($h=0, date('d'), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('75', '208');
	$pdf->Write($h=0, date('m'), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	$pdf->SetXY('98', '208');
	$pdf->Write($h=0, date('Y'), $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	
	if(isset($reca->firma_recaudador) && $reca->firma_recaudador != ''){
		$pdf->Image(APPPATH.'../tickets/files/img/firmas/'.$reca->firma_recaudador, 34, 220, 60, 35, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
	}
	
	if(isset($reca->firma_responsable) && $reca->firma_responsable != ''){
		$pdf->Image(APPPATH.'../tickets/files/img/firmas/'.$reca->firma_responsable, 140, 220, 60, 35, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
	}
			
	$file = $reca->id . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/pdf_recaudaciones/'.$file, 'F');
	
}else if(isset($reca_salon)){

	$hoy = date("d/m/Y H:i:s");
	
	$pdf = new FPDI();
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, 5,5);
	$pdf->SetAutoPageBreak(true, 30);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->AddPage();

	$pdf->SetFont('helvetica', 'B', 9);

	$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
	$pdf->Line(5, 10, 80, 10, $style);

	$pdf->SetXY('82', '8');
	$pdf->Write($h=0, "ALBARÁN DE RECAUDACIÓN", $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

	$pdf->Line(130, 10, 205, 10, $style);

	$sql = $conn->prepare('SELECT * FROM salones WHERE id = :id');
	$sql->execute(array(':id' => $reca_salon->salon));
	$salon = $sql->fetch();
	
	$sql = $conn->prepare('SELECT * FROM usuarios WHERE id = :id');
	$sql->execute(array(':id' => $reca_salon->recaudador));
	$usuario = $sql->fetch();

	$fecha = explode("-", $reca_salon->fecha);

	$tbl = '<table cellspacing="0" cellpadding="1" border="1">
			    <tr>
			        <td style="text-align: center">ESTABLECIMIENTO</td>
			    </tr>
			</table>
			<table cellspacing="0" cellpadding="1" border="1">
			    <tr>
			        <td>Fecha: '.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].'</td>
			        <td>Dirección: '.$salon["direccion"].'</td>
			    </tr>
			    <tr>
			    	<td>Nombre: '.$salon["salon"].'</td>			        
			        <td>Localidad: '.$salon["poblacion"].'</td>		        
			    </tr>
			    <tr>
			        <td>Recaudador: '.$usuario["nombre"].'</td>
			        <td>Teléfono: '.$salon["telefono"].'</td>
			    </tr>
			</table>';

	$pdf->SetXY(5, 20);
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$sql = $conn->prepare('SELECT * FROM recaudaciones_maquinas_salon_contador WHERE recaudacion = :recaudacion');
	$sql->execute(array(':recaudacion' => $reca_salon->id));
	$maquinas = $sql->fetchAll();

	foreach($maquinas as $maquina){

		$sql = $conn->prepare('SELECT * FROM maquinas WHERE id = :id');
		$sql->execute(array(':id' => $maquina['maquina']));
		$nombre = $sql->fetch();

		$fecha = explode("-", $maquina['fecha']);

		$tbl = '<table cellspacing="0" cellpadding="1" border="0">
			    <tr style="text-align: center" border="1">
			    	<td></td>
			        <td colspan="3" border="1">TOTALES</td>
			        <td></td>
			        <td colspan="3" border="1">PARCIALES</td>
			    </tr>
			    <tr>
			    	<td border="1">'.$nombre["maquina"].'</td>			        
			        <td border="1" style="text-align: center">ENTRADA TOTAL</td>
			        <td border="1" style="text-align: center">SALIDA TOTAL</td>
			        <td border="1" style="text-align: center">NETO TOTAL</td>
			        <td></td>
			        <td border="1" style="text-align: center">ENTRADA PARCIAL</td>
			        <td border="1" style="text-align: center">SALIDA PARCIAL</td>
			        <td border="1" style="text-align: center">NETO PARCIAL</td>	        
			    </tr>
			    <tr>
			        <td border="1">'.$fecha[2].'/'.$fecha[1].'/'.$fecha[0].'</td>
			        <td border="1" style="text-align: right">'.number_format($maquina["entrada_total_euros"], 2, ',', '.').'</td>
			        <td border="1" style="text-align: right">'.number_format($maquina["salida_total_euros"], 2, ',', '.').'</td>
			        <td border="1" style="text-align: right">'.number_format($maquina["neto_total_euros"], 2, ',', '.').'</td>
			        <td></td>
			        <td border="1" style="text-align: right">'.number_format($maquina["entrada_parcial_euros"], 2, ',', '.').'</td>
			        <td border="1" style="text-align: right">'.number_format($maquina["salida_parcial_euros"], 2, ',', '.').'</td>
			        <td border="1" style="text-align: right">'.number_format($maquina["neto_parcial_euros"], 2, ',', '.').'</td>
			    </tr>';

		$sql = $conn->prepare('SELECT * FROM recaudaciones_maquinas_salon_contador WHERE maquina = "'.$maquina['maquina'].'" AND recaudacion != "'.$reca_salon->id.'" ORDER BY id DESC LIMIT 1');
		$sql->execute();

		if($sql->rowCount() != 0){
			$anterior = $sql->fetch();
			$fecha2 = explode("-", $anterior['fecha']);
			$tbl .= '<tr>
				        <td border="1">'.$fecha2[2].'/'.$fecha2[1].'/'.$fecha2[0].'</td>
				        <td border="1" style="text-align: right">'.number_format($anterior["entrada_total_euros"], 2, ',', '.').'</td>
				        <td border="1" style="text-align: right">'.number_format($anterior["salida_total_euros"], 2, ',', '.').'</td>
				        <td border="1" style="text-align: right">'.number_format($anterior["neto_total_euros"], 2, ',', '.').'</td>
				        <td></td>
				        <td border="1" style="text-align: right">'.number_format($anterior["entrada_parcial_euros"], 2, ',', '.').'</td>
				        <td border="1" style="text-align: right">'.number_format($anterior["salida_parcial_euros"], 2, ',', '.').'</td>
				        <td border="1" style="text-align: right">'.number_format($anterior["neto_parcial_euros"], 2, ',', '.').'</td>
				    </tr>
				</table>';
		}else{
			$tbl .= '</table>';
		}

		$pdf->SetXY(5, $pdf->GetY());
		$pdf->writeHTML($tbl, true, false, false, false, '');
	}

	$tbl = '<table cellspacing="0" cellpadding="1" border="0">
			    <tr>
			    	<td></td>			        
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td COLSPAN="2" border="1" style="text-align: center">SUMA NETO PARCIAL</td>
			        <td border="1" style="text-align: right">'.number_format($reca_salon->bruto, 2, ',', '.').'</td>	        
			    </tr>
			</table>
			<table cellspacing="0" cellpadding="1" border="0">
			    <tr>
			    	<td></td>			        
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td COLSPAN="2" border="1" style="text-align: center">PAGOS</td>
			        <td border="1" style="text-align: right">'.number_format($reca_salon->pagos, 2, ',', '.').'</td>	        
			    </tr>
			</table>
			<table cellspacing="0" cellpadding="1" border="0">
			    <tr>
			    	<td></td>			        
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td COLSPAN="2" border="1" style="text-align: center">DATAFONO</td>
			        <td border="1" style="text-align: right">'.number_format($reca_salon->datafono, 2, ',', '.').'</td>	        
			    </tr>
			</table>
			<table cellspacing="0" cellpadding="1" border="0">
			    <tr>
			    	<td></td>			        
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td></td>
			        <td COLSPAN="2" border="1" style="text-align: center">RECAUDACIÓN NETA</td>
			        <td border="1" style="text-align: right">'.number_format($reca_salon->neto, 2, ',', '.').'</td>	        
			    </tr>
			</table>';

	$pdf->SetXY(5, $pdf->GetY());
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$tbl = '<table cellspacing="0" cellpadding="1" border="1">
			    <tr>
			        <td style="text-align: center">OBSERVACIONES</td>
			    </tr>
			    <tr>
			        <td>'.$reca_salon->comentarios.'</td>
			    </tr>
			</table>';

	$pdf->SetXY(5, $pdf->GetY());
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$file = "recaudacion_salon_adm_".$reca_salon->id . ".pdf";

	$pdf->Output(APPPATH.'../tickets/files/pdf_recaudaciones_salones/'.$file, 'F');

}

?>