<?php

namespace App\Controller;

use App\Entity\Nurses;
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
    #[Route('/index', name: 'app_nurses_index', methods: ['GET'])]
    public function index(NursesRepository $nursesRepository): JsonResponse
    {
        $nurses = $nursesRepository->getAll();
        $nursesArray = [];
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
    public function new(Request $request, NursesRepository $nursesRepository): JsonResponse
    {
        $name = $request->get('name');
        $pass = $request->get('pass');

        if (preg_match('/^(?=.*\d)(?=.*[\W_]).{6,}$/', $pass)) {
            $nursesRepository->nurseRegister($name, $pass);
            return new JsonResponse(["Register" => "Success"], Response::HTTP_OK);
        }

        return new JsonResponse(["Register" => "Failure: Invalid password"], Response::HTTP_OK);
    }

    #[Route('/show/{id}', name: 'app_nurses_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $function): JsonResponse
    {
        $nurse = $function->getRepository(Nurses::class)->find($id);
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
    public function edit($id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nurse = $entityManager->getRepository(Nurses::class)->find($id);
        if (!$nurse) {
            return new JsonResponse(["Nurse" => "Not Found"]);
        }
        $data = json_decode($request->getContent(), true);

        $nurse->setUser($data["user"]);
        $nurse->setPassword($data["pass"]);

        $entityManager->persist($nurse);
        $entityManager->flush();

        return new JsonResponse(["nurse" => "modified"], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'app_nurses_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $nurse = $entityManager->getRepository(Nurses::class)->find($id);

        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($nurse);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Nurse deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/login', name: 'app_nurse_login', methods: ['POST'])]
    public function nurseLogin(Request $request, NursesRepository $nursesRepository): JsonResponse
    {
        $name = $request->request->get('nombre');
        $pass = $request->request->get('pass');

        if (isset($name) && isset($pass)) {
            $nurse = $nursesRepository->nurseLogin($name, $pass);
            $correcto = $nurse ? true : false;
            return new JsonResponse(["login" => $correcto], Response::HTTP_OK);
        } else {
            return new JsonResponse(["login" => false], Response::HTTP_UNAUTHORIZED);
        }
    }

    #[Route('/name/{name}', name: 'nurse_list_name', methods: ['GET'])]
    public function findByName(string $name, NursesRepository $nursesRepository): JsonResponse
    {
        $nurse = $nursesRepository->findOneByName($name);

        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $nurseData = [
            'user' => $nurse->getUser(),
            'password' => $nurse->getPassword(),
        ];

        return new JsonResponse($nurseData, JsonResponse::HTTP_OK);
    }
    #[Route('/test', name: 'app_nurses_test', methods: ['GET'])]
public function test(): JsonResponse
{
    return new JsonResponse(['message' => 'Test successful'], Response::HTTP_OK);
}

}
