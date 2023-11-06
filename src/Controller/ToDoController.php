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
use App\Service\TaskService;


class ToDoController extends AbstractController
{
    private $taskService;
    public function __construct(TaskService $service) {
        $this->taskService = $service;
    }
    
    #[Route('/todo', name: 'app_todo_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $todos = $this->taskService->getTaskList();

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
        $form = $this->createForm(ToDoType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            $date = $form->get('dueDate')->getData();
            $category = $form->get('category')->getData();

            $this->taskService->createTask($title, $description, $date, $category);
            
            return $this->redirectToRoute('app_todo_list');
        }

        return $this->render('to_do/index.html.twig', [
            'add_form' => $form->createView(),
        ]);
    }

    #[Route('/todo/update/{id}', name: 'app_task_update')]
    public function update(int $id, EntityManagerInterface $entityManager,Request $request): Response
    {   
        $todo = $entityManager->getRepository(Task::class)->find($id);

        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
           
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            $date = $form->get('dueDate')->getData();
            $category = $form->get('category')->getData();
            $this->taskService->editTask($id, $title, $description, $date, $category);

            return $this->redirectToRoute('app_todo_list');
        }

        return $this->render('to_do/index.html.twig', ['add_form' => $form->createView()]);
    }

    #[Route('/todo/delete/{id}', name: 'app_task_delete')]
    public function delete(int $id ): Response
    {
    
        $this->taskService->deleteTask($id);
        
        return $this->redirectToRoute('app_todo_list');
    }


}