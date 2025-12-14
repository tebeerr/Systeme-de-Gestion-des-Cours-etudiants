<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Inscription;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/classe')]
final class ClasseController extends AbstractController
{
    #[Route(name: 'app_classe_index', methods: ['GET'])]
    public function index(ClasseRepository $classeRepository): Response
    {
        return $this->render('classe/index.html.twig', [
            'classes' => $classeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_classe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classe);
            
            // Handle student selection - create Inscription records
            $students = $form->get('students')->getData();
            if ($students) {
                foreach ($students as $student) {
                    $inscription = new Inscription();
                    $inscription->setClasse($classe);
                    $inscription->setUser($student);
                    $inscription->setDate(new \DateTime());
                    $inscription->setStatus('Active');
                    $entityManager->persist($inscription);
                }
            }
            
            $entityManager->flush();

            $studentCount = $students ? count($students) : 0;
            $message = 'Class created successfully!';
            if ($studentCount > 0) {
                $message .= " {$studentCount} student(s) added to the class.";
            }
            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classe_show', methods: ['GET'])]
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_classe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Class updated successfully!');

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classe_delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($classe);
            $entityManager->flush();

            $this->addFlash('success', 'Class deleted successfully!');
        }

        return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
