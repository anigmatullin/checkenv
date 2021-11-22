<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class ReportEmail
{
    protected $dsn;
    protected $from;
    protected $to;

    protected $transport;
    protected $mailer;

    public function __construct()
    {
        $this->dsn = $_ENV['MAILER_DSN'];
        $this->from = $_ENV['MAILER_FROM'];
        $this->to = $_ENV['MAILER_TO'];

        $this->transport = Transport::fromDsn($this->dsn);
        $this->mailer = new Mailer($this->transport);
    }

    public function send($subject, $html, $text = "")
    {
        $email = (new Email())
            ->from($this->from)
            ->to($this->to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($text)
            ->html($html);

        $this->mailer->send($email);
    }

}

