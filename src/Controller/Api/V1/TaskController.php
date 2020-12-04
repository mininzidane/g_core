<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\Api\AbstractController;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="data_v1_get_task_list", methods={"GET"})
     */
    public function index(
        TaskRepository $taskRepository,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = [];
        $tasks = $taskRepository->findTodayTasks($user);
        foreach ($tasks as $task) {
            $data[] = $normalizer->normalize($task, 'json', ['groups' => 'task_details']);
        }

        return $this->successResponse([
            'data' => $data,
        ]);
    }

    /**
     * @Route("/tasks", name="data_v1_create_task", methods={"POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        $title = $request->get('title');
        $description = $request->get('description');

        /** @var User $user */
        $user = $this->getUser();

        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description);
        $user->addTask($task);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->successResponse([
            'data' => $normalizer->normalize($task, 'json', ['groups' => 'task_details']),
        ]);
    }

    /**
     * @Route("/tasks/{id}/set-status/{status}", name="data_v1_task_set_status", methods={"GET"})
     */
    public function setStatus(
        Task $task,
        string $status,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== $task->getUser()) {
            throw new AccessDeniedHttpException('It is not your task!');
        }

        // todo add validation
        $task->setStatus($status);
        $entityManager->flush();

        return $this->successResponse([
            'data' => $normalizer->normalize($task, 'json', ['groups' => 'task_details']),
        ]);
    }
}
