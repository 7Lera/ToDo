<?php

namespace App\Controller;

date_default_timezone_set('Europe/Bucharest');
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Form\TodoType;

class ToDoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $todos = $taskRepository->findAll();

        return $this->render('to_do/todos.html.twig', [
            'todos' => $todos,
        ]);
    }


    #[Route('/todo/{id}', name: 'app_todo_view')]
    public function view(int $id, TaskRepository $taskRepository): Response
    {
        $todo = $taskRepository->find($id);

        return $this->render('to_do/todo.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/task/create', name: 'app_todo_create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TodoType::class, $task, ['method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->isMethod('POST')) {
                $task->setCreatedAt(new \DateTime());
                $entityManager->persist($task);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_todo_list');
        }

        return $this->render('to_do/index.html.twig', [
            'add_form' => $form->createView(),
        ]);
    }

    #[Route('/todo/update/{id}', name: 'app_task_update')]
    public function update(int $id, EntityManagerInterface $entityManager, Request $request, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Task not found');
        }

        $form = $this->createForm(ToDoType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_list');
        }

        return $this->render('to_do/index.html.twig', [
            'add_form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/todo/delete/{id}', name: 'app_task_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Task not found');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_todo_list');
    }

    

}