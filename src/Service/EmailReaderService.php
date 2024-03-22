<?php

namespace App\Service;

use PhpImap\Mailbox as Mailbox;


class EmailReaderService
{
    const MAIL_FROM = 'noreply@smsapi.pl';
    const IMAP_CONFIG = [
        'host' => 'imap.iq.pl',
        'port' => 993,
        'flags' => '/imap/ssl',
        'mailbox' => 'INBOX',
        'username' => 'sms-rekrutacja@ironteam-raporty.pl',
        'password' => 'sLdVcoRBu23ltiRIrwVt',
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public function getEmailsFromImap(): array
    {
//        $mailbox1 = new Mailbox("{imap.iq.pl:993/imap/ssl}INBOX", "sms-rekrutacja@ironteam-raporty.pl", "sLdVcoRBu23ltiRIrwVt");
        try {
            $mailbox = new Mailbox("{" . self::IMAP_CONFIG['host'] . ":" . self::IMAP_CONFIG['port'] . self::IMAP_CONFIG['flags'] . "}" . self::IMAP_CONFIG['mailbox'], self::IMAP_CONFIG['username'], self::IMAP_CONFIG['password']);
            $mailsIds = $mailbox->searchMailbox('ALL');
            $emailsData = [];

            foreach ($mailsIds as $mailId) {
                $email = $mailbox->getMail($mailId, false);

                if ($email->fromAddress == self::MAIL_FROM) {
                    $emailsData[] = $email->textHtml;
                }
            }
            return $emailsData;
        } catch (\Exception $e) {
            throw new \Exception('Error while reading emails: ' . $e->getMessage());
        }
    }
}