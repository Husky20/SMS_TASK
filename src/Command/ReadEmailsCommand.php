<?php

namespace App\Command;

use App\Service\MessageFromEmailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:read-emails',
    description: 'Add a short description for your command',
)]
class ReadEmailsCommand extends Command
{
    protected static $defaultName = 'app:read-emails';
    private $emailReaderService;

    public function __construct(MessageFromEmailService $emailReaderService)
    {
        $this->emailReaderService = $emailReaderService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Reads emails from the mailbox and save them to the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->emailReaderService->saveMessagesFromEmails();
        } catch (\Exception $e) {
            $output->writeln('An error occurred while reading emails: ' . $e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln('Emails read successfully.');

        return Command::SUCCESS;
    }
}
