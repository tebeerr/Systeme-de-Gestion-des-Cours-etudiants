<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Examen;
use App\Repository\ClasseRepository;
use App\Repository\CoursRepository;
use App\Repository\ExamenRepository;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(
        ClasseRepository $classeRepository,
        CoursRepository $coursRepository,
        ExamenRepository $examenRepository,
        InscriptionRepository $inscriptionRepository
    ): Response {
        $stats = [
            'classes' => $classeRepository->count([]),
            'cours' => $coursRepository->count([]),
            'examens' => $examenRepository->count([]),
            'inscriptions' => $inscriptionRepository->count([]),
        ];

        return $this->render('dashboard/admin.html.twig', [
            'stats' => $stats,
        ]);
    }

    #[Route('/student/dashboard', name: 'app_student_dashboard')]
    #[IsGranted('ROLE_STUDENT')]
    public function studentDashboard(
        CoursRepository $coursRepository,
        ExamenRepository $examenRepository,
        InscriptionRepository $inscriptionRepository
    ): Response {
        $user = $this->getUser();
        
        // Get student's inscriptions
        $inscriptions = $inscriptionRepository->findBy(['user' => $user]);
        
        // Get courses from student's classes
        $courses = [];
        foreach ($inscriptions as $inscription) {
            if ($inscription->getClasse()) {
                $classeCourses = $coursRepository->findBy(['classe' => $inscription->getClasse()]);
                foreach ($classeCourses as $course) {
                    if (!in_array($course, $courses, true)) {
                        $courses[] = $course;
                    }
                }
            }
        }
        
        // Get student's exams
        $exams = $examenRepository->findBy(['user' => $user]);

        return $this->render('dashboard/student.html.twig', [
            'courses' => $courses,
            'exams' => $exams,
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/professor/dashboard', name: 'app_professor_dashboard')]
    #[IsGranted('ROLE_PROFESSOR')]
    public function professorDashboard(
        CoursRepository $coursRepository,
        ExamenRepository $examenRepository
    ): Response {
        $stats = [
            'cours' => $coursRepository->count([]),
            'examens' => $examenRepository->count([]),
        ];

        return $this->render('dashboard/professor.html.twig', [
            'stats' => $stats,
        ]);
    }
}

