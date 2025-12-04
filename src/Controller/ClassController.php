<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClassController extends AbstractController
{
    #[Route('/class', name: 'app_class')]
    public function classes(ClasseRepository $cr): Response
    {
        $classes = $cr->findAll();
        return $this->render('class/list.html.twig', [
            'classes' => $classes,
        ]);
    }

    #[Route('/class/new', name: 'app_class_new')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classe);
            $em->flush();

            $this->addFlash('success', '');

            return $this->redirectToRoute('app_class');
        }

        return $this->render('class/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}