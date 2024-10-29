<?php

namespace App\Controller;

use App\Entity\Nurses;
use App\Form\NursesType;
use App\Repository\NursesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nurses')]
final class NursesController extends AbstractController
{
    #[Route(name: 'app_nurses_index', methods: ['GET'])]
    public function index(NursesRepository $nursesRepository): Response
    {
        $nurses = $nursesRepository->getAll();
        foreach ($nurses as $nurse) {
            $nursesArray[] = [
                'id' => $nurse->getId(),
                'user' => $nurse->getUser(),
                'pass' => $nurse->getPassword(),
            ];
        }
        return new JsonResponse($nursesArray, Response::HTTP_OK);
    }

    #[Route('/new', name: 'app_nurses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nurse = new Nurses();
        $form = $this->createForm(NursesType::class, $nurse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nurse);
            $entityManager->flush();

            return $this->redirectToRoute('app_nurses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nurses/new.html.twig', [
            'nurse' => $nurse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nurses_show', methods: ['GET'])]
    public function show(Nurses $nurse): Response
    {
        return $this->render('nurses/show.html.twig', [
            'nurse' => $nurse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nurses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nurses $nurse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NursesType::class, $nurse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nurses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nurses/edit.html.twig', [
            'nurse' => $nurse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nurses_delete', methods: ['POST'])]
    public function delete(Request $request, Nurses $nurse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nurse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($nurse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nurses_index', [], Response::HTTP_SEE_OTHER);
    }
}
