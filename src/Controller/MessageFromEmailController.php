<?php

namespace App\Controller;

use App\Service\MessageFromEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageFromEmailController extends AbstractController
{
    #[Route('/message/from/email', name: 'app_message_from_email')]
    public function index(MessageFromEmailService $emailReaderService): Response
    {
        $emailReaderService->saveMessagesFromEmails();

        return $this->redirectToRoute('app_message_index');
    }
}
