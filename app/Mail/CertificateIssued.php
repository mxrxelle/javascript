<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;

class CertificateIssued extends Mailable
{
    use Queueable, SerializesModels;

    public User $student;
    public Course $course;
    public Certificate $certificate;
    public string $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(User $student, Course $course, Certificate $certificate, string $pdfPath)
    {
        $this->student     = $student;
        $this->course      = $course;
        $this->certificate = $certificate;
        $this->pdfPath     = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎓 Congratulations! Your Certly Certificate is Ready',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate',
            with: [
                'studentName'    => $this->student->name,
                'courseName'     => $this->course->title,
                'certificateUid' => $this->certificate->certificate_uid,
                'issuedAt'       => $this->certificate->issued_at->format('F j, Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Certificate_' . $this->certificate->certificate_uid . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
