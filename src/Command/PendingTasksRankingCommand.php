<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\UserTaskRanking;
use App\PendingTaskRanking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PendingTasksRankingCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected static $defaultName = 'app:user-task-ranking';

    protected function configure(): void
    {
        $this
            ->setDescription('Description of your command')
            ->setHelp('This command does...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $executeTask = new PendingTaskRanking($this->entityManager);
        $executeTask->connectAndSaveRanking();

        $data = [];
        $repository = $this->entityManager->getRepository(UserTaskRanking::class);
        $entities = $repository->findAll();
        foreach ($entities as $entity) {
            $data[] = [
                'name' => $entity->getName(),
                'username' => $entity->getUsername(),
                'email' => $entity->getEmail(),
                'pending_tasks' => $entity->getPendingTasks(),
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Username', 'Email', 'Pending tasks'])
            ->setRows($data);
        $table->render();

        return Command::SUCCESS;
    }
}