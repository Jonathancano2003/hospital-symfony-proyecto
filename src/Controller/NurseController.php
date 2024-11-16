<?php

namespace App\Controller;

use App\Repository\NursesRepository;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Nurses;

#[Route(path: '/nurse', name: 'nurses')]
class NurseController extends AbstractController
{
    #[Route('/index', name: 'app_nurses_index', methods: ['GET'])]
    public function getAllNurses(NursesRepository $nRepository): JsonResponse
    {
        $nurses = $nRepository->getAll();
        foreach ($nurses as $nurse) {
            $nursesArray[] = [
                'id' => $nurse->getId(),
                'user' => $nurse->getUser(),
                'pass' => $nurse->getPassword(),
            ];
        }
        return new JsonResponse($nursesArray, Response::HTTP_OK);
    }


    #[Route('/login', name: 'app_nurse_login', methods:["POST"])]
    public function nurseLogin(Request $request, NursesRepository $nursesRepository): JsonResponse
    { {
            $name = $request->request->get('name');
            $pass = $request->request->get( 'pass');
            if(isset($name) && isset($pass)){
            $correcto = false;
          
            $nurse = $nursesRepository->nurseLogin($name, $pass);
            
            if($nurse){
                $correcto = true;
            }
            return new JsonResponse(["login" => $correcto], Response::HTTP_OK);
            }else{
                return new JsonResponse(["login" => false], Response::HTTP_UNAUTHORIZED);
            }
        }
    }
    #[Route('/name/{name}', name: 'nurse_list_name', methods: ['GET'])]
    public function findByName(string $name, NursesRepository $NursesRepository): JsonResponse
    {
        $nurse = $NursesRepository->findOneByName($name);
    
        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $nurseData = [
            'user' => $nurse->getUser(),
            'password' => $nurse->getPassword(),
        ];
    
        return new JsonResponse($nurseData, JsonResponse::HTTP_OK);
    }

    //CRUD-------

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

    #[Route('/{id}', name: 'app_nurses_edit', methods: ['PUT'])]
    public function edit($id, Request $request, Nurses $nurseId = null, EntityManagerInterface $entityManager): JsonResponse
    {
        $nurseId = $entityManager->getRepository(Nurses::class)->find($id);
        if(!$nurseId){
            return new JsonResponse(["Nurse" => "Not Found"]);
        }
        $data = json_decode($request->getContent(), true);

        $nurseId->setUser($data["user"]);
        $nurseId->setPassword($data["pass"]);

        $entityManager->persist($nurseId);
        $entityManager->flush();

        return new JsonResponse(["nurse" => "modified"], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_nurses_delete', methods: ['DELETE'])]
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
    
}
