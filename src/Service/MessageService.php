<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllMessagesSortedAndFiltered(string $sortBy = 'receivedDate', string $orderBy = 'DESC', array $filters = []): array
    {
        $criteria = [];

        if ($filters['sender']) {
            $criteria['sender'] = $filters['sender'];
        }
        if ($filters['content']) {
            $criteria['content'] = $filters['content'];
        }
        if ($filters['startDate']) {
            $criteria['receivedDate'] = $filters['startDate'];
        }
        if ($filters['endDate']) {
            $criteria['receivedDate'] = $filters['endDate'];
        }
        if ($filters['id']) {
            $criteria['id'] = $filters['id'];
        }

        $repository = $this->entityManager->getRepository(Message::class);
        $messages = $repository->findBy($criteria, [$sortBy => $orderBy]);

        return $messages;
    }
}