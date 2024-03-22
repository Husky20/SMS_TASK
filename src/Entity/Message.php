<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sender = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $receivedDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getReceivedDate(): ?\DateTimeImmutable
    {
        return $this->receivedDate;
    }

    public function setReceivedDate(\DateTimeImmutable $receivedDate): static
    {
        $this->receivedDate = $receivedDate;

        return $this;
    }
}
