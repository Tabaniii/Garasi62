<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarUnpublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sellerName;
    public $carName;
    public $carBrand;
    public $carModel;
    public $reason;
    public $reportReason;
    public $reportMessage;
    public $reporterName;
    public $reportDate;
    public $reportUrl;
    public $reportId;

    /**
     * Create a new message instance.
     */
    public function __construct($sellerName, $carName, $carBrand, $carModel, $reason, $reportReason, $reportMessage, $reporterName, $reportDate, $reportUrl, $reportId)
    {
        $this->sellerName = $sellerName;
        $this->carName = $carName;
        $this->carBrand = $carBrand;
        $this->carModel = $carModel;
        $this->reason = $reason;
        $this->reportReason = $reportReason;
        $this->reportMessage = $reportMessage;
        $this->reporterName = $reporterName;
        $this->reportDate = $reportDate;
        $this->reportUrl = $reportUrl;
        $this->reportId = $reportId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Mobil {$this->carName} Di-Unpublish - Garasi62",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.car-unpublished',
            with: [
                'sellerName' => $this->sellerName,
                'carName' => $this->carName,
                'carBrand' => $this->carBrand,
                'carModel' => $this->carModel,
                'reason' => $this->reason,
                'reportReason' => $this->reportReason,
                'reportMessage' => $this->reportMessage,
                'reporterName' => $this->reporterName,
                'reportDate' => $this->reportDate,
                'reportUrl' => $this->reportUrl,
                'reportId' => $this->reportId,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

