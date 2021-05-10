<?php
namespace App\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class PDFController extends Controller
{
    private $fpdf;
 
    public function __construct()
    {
         
    }
 
    public function export()
    {
        $this->fpdf = new Fpdf;
         $this->fpdf->AddPage();
        $this->fpdf->SetFont('Arial','B',12);		
    //    $this->fpdf->AddPage("L", ['100', '100']);


    $heading = array('#','Donor','created By','Blood Group','status');

	foreach($heading as $column_heading){
		$this->fpdf->Cell(40,10,$column_heading,1);
	}	

        // foreach($result as $row) {
        // 	$this->fpdf->Ln();
        // 	foreach($row as $column)
        // 		$this->fpdf->Cell(95,12,$column,1);
        // }
        
        // $this->fpdf->Text(10, 10, "Hello FPDF");       
         
        $this->fpdf->Output();
        exit;
    }
}
