<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf {

	function return_pdf($empresa,$visitas,$usuario){
	
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
    
    include 'PDF/fpdi-tcpdf.php';
    
    return true;
    
  }
  
  function return_pdf_promos($promos,$query){
  	
  	error_reporting(E_ERROR | E_PARSE | E_NOTICE);
    
    include 'PDF/fpdi-tcpdf.php';
    
    return true;
  	
  }

  function return_pdf_operadoras($id,$informe_operadora){
    
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
    
    include 'PDF/fpdi-tcpdf.php';
    
    return $file;
    
  }

  public function return_pdf_carnet_adm($generar_carnet, $u){
    
    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
      
      include 'PDF/fpdi-tcpdf.php';
      
      return true;    
  }
}