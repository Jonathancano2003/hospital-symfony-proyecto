<?php

namespace App\Controller;

use App\Repository\NursesRepository;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class NurseController extends AbstractController
{
   

    #[Route(path: '/index', name: 'app_nurse_getAll')]
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
        return new JsonResponse($nursesArray, JsonResponse::HTTP_OK);
    }


    #[Route('/login', name: 'app_nurse_login', methods:["POST"])]
    public function nurseLogin(Request $request, NursesRepository $nursesRepository): JsonResponse
    { {
            $name = $request->request->get('nombre');
            $pass = $request->request->get( 'pass');
            $correcto = false;
          

            $nurse = $nursesRepository->nurseLogin($name, $pass);
            

          
            if($nurse){
                $correcto = true;
            }
            return new JsonResponse(["login" => $correcto], Response::HTTP_OK);
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
    
}
