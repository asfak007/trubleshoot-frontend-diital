<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\PDF;


class BookingController extends Controller
{
    public function bookingList($statusSlug = null){
        $query = Booking::with('customer');
        if ($statusSlug) {
            switch ($statusSlug) {
                case 'progressing':
                     $query->where('status', 'progressing');
                    break;
                case 'completed':
                    $query->where('status', 'completed');
                    break;
                case 'canceled':
                    $query->where('status', 'cancelled');
                    break;
                case 'pending':
                    $query->where('status','pending');
                    break;
                case 'rejected':
                    $query->where('status','rejected');
                    break;

                // Add more cases if needed
            }

        }
        $bookings = $query->get();


        $progressingCount = Booking::where('status', 'progressing')->count();
        $completedCount = Booking::where('status', 'completed')->count();
        $canceledCount = Booking::where('status', 'cancelled')->count();
        $pendingCount = Booking::where('status', 'pending')->count();

        return view('admin.page.booking.list',compact('bookings','progressingCount', 'completedCount', 'canceledCount', 'pendingCount'));
    }
    public function details($id){

        $details = Booking::with('provider','handyman')->whereId($id)->first();
        $metadata = json_decode($details->metadata, true);

        // Add decoded metadata to the details array
        $details['metadata'] = $metadata;



        return view('admin.page.booking.details',compact('details'));
    }
    public function invoice(Booking $booking){

             return view('admin.page.booking.invice',compact('booking'));


    }

    public function downloadPDF(Booking $booking)
    {
        // Generate the PDF content from the Blade template
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.page.booking.download', compact('booking'))->setOptions(['defaultFont' => 'sans-serif']);

        // Set paper size and orientation (optional)
        $pdf->setPaper('A4', 'portrait');

        // Download the PDF with a specific file name
        return $pdf->download('Invoice.pdf');
    }
}
