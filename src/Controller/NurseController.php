<?php

namespace App\Controller;

use App\Repository\NursesRepository;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class NurseController extends AbstractController
{
    private function allNurses(): array
    {
        $jsonData = '[{"user":"Emmeline","password":"iM5}~tp/"},
        {"user":"Susana","password":"wP7@bQp@|S~HlhI"},
        {"user":"Aharon","password":"zE4)U\'ptR"},
        {"user":"Ardath","password":"eE3/}$}Fh5"},
        {"user":"Cyrill","password":"pQ7?\'1+$<l"}]';

        return json_decode($jsonData, true); // Corrección aquí
    }

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
            $nurses = $this->allNurses();

            $nurse = $nursesRepository->nurseLogin($name, $pass);
            

            // if (isset($name) && isset($pass)) {
            //     for ($i = 0; $i < count($nurses); $i++) {
            //         $NurseName = $nurses[$i]["user"];
            //         $NursePass = $nurses[$i]["password"];
            //         if ($name == $NurseName && $pass == $NursePass) {
            //             $correcto = true;
            //             break;
            //         }
            //     }
            //     return new JsonResponse(["login" => $correcto], Response::HTTP_OK);
            // } else {
            //     return new JsonResponse(["login" => "Credential Missing"], Response::HTTP_OK);
            // }
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
