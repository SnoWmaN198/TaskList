<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;

class TaskController extends AbstractController
{
    
    // function to display all tasks 
    /**
     * @Route("/display", name="display_tasks")
     */
    public function display()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        return $this->render('task/create.html.twig', ['tasks'=>$tasks]);
    }
    
    // function to create a task
    /**
     * @Route("/create", name="create_task", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $taskTitle = $request->request->get('taskTitle');
        
        $task = new Task();
        $task->setTitle($taskTitle);
        
        if (empty($taskTitle)) {
            
            return $this->redirectToRoute('display_tasks');
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();
        
        return $this->redirectToRoute('display_tasks');
    }
    
    // function to switch the status of a task in the list
    /**
     * @Route("/status/{id}", name="task_status")
     */
    public function status(Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        
        $task->setStatus(! $task->getStatus());
        $entityManager->flush();
        
        return $this->redirectToRoute('display_tasks');
    }
    
    // function to delete a task
    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete(Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $entityManager->remove($id);
        $entityManager->flush();
        
        return $this->redirectToRoute('display_tasks');
    }
    
}
