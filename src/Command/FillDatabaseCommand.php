<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TaskGenerator;
use App\Service\UserCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillDatabaseCommand extends Command
{
    protected static $defaultName = 'app:fill:db';

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserCreator
     */
    private $userCreator;
    /**
     * @var TaskGenerator
     */
    private $taskGenerator;

    public function __construct(
        EntityManagerInterface $em,
        UserCreator $userCreator,
        TaskGenerator $taskGenerator
    )
    {
        parent::__construct(null);
        $this->em = $em;
        $this->userCreator = $userCreator;
        $this->taskGenerator = $taskGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userCreator->create('root', '123456');

        $tasks = $this->taskGenerator->generateDayPack();
        foreach ($tasks as $task) {
            $user->addTask($task);
            $this->em->persist($task);
        }

        $this->em->flush();
        $output->writeln('Database filled with sample task data');

        return 0;
    }
}
