<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\Models\Requisition;
use App\Models\RequisitionItem;

class PDFController extends Controller
{
    public function downloadPDF()
    {
        $requisitionheading = Requisition::find(1); // Adjust this based on your logic to retrieve requisition heading data
        $requisitionlist = RequisitionItem::all(); // Adjust this based on your logic to retrieve requisition list data

        $pdf = new Dompdf();
        $pdf->loadHTML(view('pdf.requisition', compact('requisitionheading', 'requisitionlist')));

        // Set the paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Render the PDF
        $pdf->render();

        // Output the generated PDF to the browser
        return $pdf->stream('requisition.pdf');
    }
}
