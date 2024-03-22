<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Service\EmailReaderService;
use App\Service\MessageFromEmailService;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use PharIo\Manifest\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/message')]
class MessageController extends AbstractController
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $sortBy = $request->query->get('sortBy', 'receivedDate');
        $orderBy = $request->query->get('orderBy', 'DESC');

        $filters = [
            'sender' => $request->query->get('sender'),
            'content' => $request->query->get('content'),
            'startDate' => $request->query->get('startDate'),
            'endDate' => $request->query->get('endDate'),
            'id' => $request->query->get('id')
        ];

        $messages = $this->messageService->getAllMessagesSortedAndFiltered($sortBy, $orderBy, $filters);

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
            'sortBy' => $sortBy,
            'orderBy' => $orderBy,
            'sender' => $filters['sender'],
            'content' => $filters['content'],
            'startDate' => $filters['startDate'],
            'endDate' => $filters['endDate'],
            'id' => $filters['id']
        ]);
    }

    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
