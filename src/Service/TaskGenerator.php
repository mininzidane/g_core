<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Task;

class TaskGenerator
{
    public const TITLES = [
        'Brush teeth',
        'Wash dish',
        'Wash the dishes',
        'Clean the house',
        'Go to the doctor',
        'Pick up son from school',
        'Visit a friend',
        'Come to party',
        'Buy products',
        'Take out the trash',
    ];

    /**
     * @param int $count
     * @return Task[]
     */
    public function generateDayPack(int $count = 5): array
    {
        $titles = self::TITLES;
        \shuffle($titles);

        $tasks = [];
        foreach (\range(1, $count) as $i) {
            $title = \array_shift($titles);
            $task = new Task();
            $task->setTitle($title);
            $tasks[] = $task;
        }

        return $tasks;
    }
}
