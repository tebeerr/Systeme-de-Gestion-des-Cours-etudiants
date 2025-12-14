<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Inscription;
use App\Entity\User;
use App\Repository\ClasseRepository;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/assign-students')]
#[IsGranted('ROLE_ADMIN')]
class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_assign_students', methods: ['GET', 'POST'])]
    public function assignStudents(
        Request $request,
        UserRepository $userRepository,
        ClasseRepository $classeRepository,
        InscriptionRepository $inscriptionRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $students = $userRepository->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_STUDENT%')
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();

        $classes = $classeRepository->findAll();

        if ($request->isMethod('POST')) {
            $studentId = $request->request->get('student_id');
            $classeId = $request->request->get('classe_id');

            if ($studentId && $classeId) {
                $student = $userRepository->find($studentId);
                $classe = $classeRepository->find($classeId);

                if ($student && $classe) {
                    // Check if inscription already exists
                    $existingInscription = $inscriptionRepository->findOneBy([
                        'user' => $student,
                        'classe' => $classe
                    ]);

                    if (!$existingInscription) {
                        $inscription = new Inscription();
                        $inscription->setUser($student);
                        $inscription->setClasse($classe);
                        $inscription->setDate(new \DateTime());
                        $inscription->setStatus('Active');

                        $entityManager->persist($inscription);
                        $entityManager->flush();

                        $this->addFlash('success', "Student {$student->getEmail()} has been assigned to class {$classe->getLibelle()}!");
                    } else {
                        $this->addFlash('warning', "Student {$student->getEmail()} is already assigned to class {$classe->getLibelle()}!");
                    }
                }
            }

            return $this->redirectToRoute('app_assign_students');
        }

        // Get all inscriptions to show current assignments
        $inscriptions = $inscriptionRepository->findAll();

        return $this->render('admin/assign_students.html.twig', [
            'students' => $students,
            'classes' => $classes,
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_remove_student_from_class', methods: ['POST'])]
    public function removeStudentFromClass(
        Request $request,
        Inscription $inscription,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('remove'.$inscription->getId(), $request->getPayload()->getString('_token'))) {
            $studentEmail = $inscription->getUser()->getEmail();
            $classeName = $inscription->getClasse()->getLibelle();
            
            $entityManager->remove($inscription);
            $entityManager->flush();

            $this->addFlash('success', "Student {$studentEmail} has been removed from class {$classeName}!");
        }

        return $this->redirectToRoute('app_assign_students');
    }
}

