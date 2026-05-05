<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath;
    public $fileName;

    /**
     * Create a new message instance.
     */
    public function __construct($filePath, $fileName)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Get the message envelope.
     */
    public function Envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cadangan Database Madrasah Digital - ' . date('d M Y H:i'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<h3>Halo Admin,</h3><p>Terlampir adalah file cadangan database terbaru untuk aplikasi Madrasah Digital.</p><p>Mohon simpan file ini di tempat yang aman.</p><br><p>Salam,<br>Sistem Otomatis</p>",
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filePath)
                ->as($this->fileName)
                ->withMime('application/zip'),
        ];
    }
}
