<?php
require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

class concat_pdf extends FPDI {

    var $files = array();

    function setFiles($files) {
        $this->files = $files;
    }

    function concat() {
        foreach($this->files AS $file) {
            $pagecount = $this->setSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                 $tplidx = $this->ImportPage($i);
                 $s = $this->getTemplatesize($tplidx);
                 $this->AddPage($s['w'] > $s['h'] ? 'L' : 'P', array($s['w'], $s['h']));
                 $this->useTemplate($tplidx);
            }
        }
    }

}

$pdf = new concat_pdf();
$pdf->setFiles(array('trespaginas.pdf'));
$pdf->concat();
//jorge 2012-11-14-12-58
//he tenido que añadir esto para que no de problemas de buffer
ob_start();
$pdf->Output('newpdf.pdf', 'D');
?>