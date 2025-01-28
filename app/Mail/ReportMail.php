<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;
    public $pdf;

    public function __construct($reportData, $pdf)
    {
        $this->reportData = $reportData;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->view('emails.report') // Path to your email view
                    ->subject('Your Generated Report')
                    ->with(['reportData' => $this->reportData])
                    ->attachData($this->pdf, 'report.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}

