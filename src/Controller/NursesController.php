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

    #[Route('/new', name: 'app_nurses_new', methods: ['POST'])]
    public function new(Request $request, NursesRepository $nursesRepository): Response
    {
        $name = $request->get('nombre');
        $pass = $request->get('pass');
        $nursesRepository->nurseRegister($name,$pass);

        return new JsonResponse(["Register" => "Success"], Response::HTTP_OK);
    }
    
    #[Route('/show/{id}', name: 'app_nurses_show', methods: ['GET'])]
    public function show(int $id, NursesRepository $nursesRepository): Response
    {
        $nurse = $nursesRepository->findOneById($id);
        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $arrayNurse = [
            'user' => $nurse->getUser(),
            'password' => $nurse->getPassword(),
        ];
        return new JsonResponse($arrayNurse, Response::HTTP_OK);
    }

    #[Route('/edit/{id}', name: 'app_nurses_edit', methods: ['PUT'])]
    public function edit($id, Request $request, Nurses $nurse, EntityManagerInterface $entityManager): Response
    {

        $nurseId = $entityManager->getRepository(Nurses::class)->find($id);
        $data = json_decode($request->getContent(), true);

        $nurseId->setUser($data["user"]);
        $nurseId->setPassword($data["pass"]);

        $entityManager->persist($nurseId);
        $entityManager->flush();

        return new JsonResponse(["nurse" => "modified"]);

        // $form = $this->createForm(NursesType::class, $nurse);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_nurses_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('nurses/edit.html.twig', [
        //     'nurse' => $nurse,
        //     'form' => $form,
        // ]);
    }

    #[Route('/delete/{id}', name: 'app_nurses_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $nurse = $entityManager->getRepository(Nurses::class)->find($id);

        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($nurse);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Nurse deleted successfully'], Response::HTTP_OK);
    }
}
