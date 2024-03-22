<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class MessageFromEmailService
{
    private $entityManager;
    private $emailReaderService;

    public function __construct(EntityManagerInterface $entityManager, EmailReaderService $emailReaderService)
    {
        $this->entityManager = $entityManager;
        $this->emailReaderService = $emailReaderService;
    }

    public function saveMessagesFromEmails()
    {
        $emails = $this->emailReaderService->getEmailsFromImap();
        $messagesData = $this->prepareData($emails);
        $entityManager = $this->entityManager;

        foreach ($messagesData as $messageData) {
            $message = new Message();
            $message->setSender($messageData['sender']);

            $message->setContent($messageData['content']);
            $message->setReceivedDate(new \DateTimeImmutable($messageData['date']));

            $entityManager->persist($message);
        }

        $entityManager->flush();

    }

    private function prepareData($emails): array
    {
        $patterns = [
            '/<strong>Nadawca:<\/strong>\s*([0-9]+)\s*/',
            '/<strong>Odbiorca:<\/strong>\s*([0-9]+)\s*/',
            '/<strong>Treść odebranej wiadomości:<\/strong>\s*([^<]+)\s*/',
            '/<strong>Data:<\/strong>\s*([0-9:-]+\s+[0-9:]+)/'
        ];
        $keys = ['sender', 'recipient', 'content', 'date'];
        $emailsData = [];
        foreach ($emails as $email) {
            $data = [];
            foreach ($patterns as $pattern) {
                preg_match($pattern, $email, $matches);
                $data[] = $matches[1];
            }
            $emailsData[] = array_combine($keys, $data);
        }

        return $emailsData;
    }

}