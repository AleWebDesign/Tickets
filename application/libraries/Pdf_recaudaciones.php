<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_recaudaciones {

	function return_pdf_recaudaciones($reca,$u){
	
	    error_reporting(E_ERROR | E_PARSE | E_NOTICE);
	    
	    include 'PDF_recaudaciones/fpdi-tcpdf.php';
	    
	    return true;
	    
	}

	function return_pdf_recaudacion_salon($reca_salon,$u){

		error_reporting(E_ERROR | E_PARSE | E_NOTICE);
	    
	    include 'PDF_recaudaciones/fpdi-tcpdf.php';
	    
	    return true;

	}
}