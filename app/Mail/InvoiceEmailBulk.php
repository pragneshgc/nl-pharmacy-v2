<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceEmailBulk extends Mailable
{
    use Queueable, SerializesModels;

    private $id;
    private $toCompanyName;
    private $fromCompanyName;
    private $files;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $toCompanyName, $fromCompanyName, $files)
    {
        $this->id = $id;
        $this->toCompanyName = $toCompanyName;
        $this->fromCompanyName = $fromCompanyName;
        $this->files = $files;
        $this->subject = "Invoice #$id for $toCompanyName from $fromCompanyName";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from('info@hrhealthcare.group', 'HR Healthcare Pharmacy')
            ->bcc('info@hrhealthcare.group', 'HR Healthcare Pharmacy')
            ->subject($this->subject)->view('email.invoice')->with(
                [
                    'id' => $this->id,
                    'toCompanyName' => $this->toCompanyName,
                    'fromCompanyName' => $this->fromCompanyName,
                ]
            );

        $i = 0;

        foreach ($this->files as $file) {
            $i++;
            $mail->attach($file, [
                'as' => "Invoice #$this->id-$i.pdf",
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
