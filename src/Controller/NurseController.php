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
    public function getAll(): JsonResponse
    {
        $credenciales = $this->allNurses();
        return $this->json($credenciales);
    }


    #[Route('/nurse/login', name: 'app_nurse_login')]
    public function index(): Response
    { {
            $correcto = false;
            $users = $this->allNurses();

            if (isset($_POST["nombre"]) && isset($_POST["pass"])) {
                $nombre = "Antonio";
                $pass = "12345678";
                for ($i = 0; $i < count($users); $i++) {
                    $nombre = $users[$i]["user"];
                    $pass = $users[$i]["password"];
                    if ($_POST["nombre"] == $nombre && $pass == $_POST["pass"]) {
                        $correcto = true;
                        break;
                    }
                }
                if ($correcto == false) {
                    echo "Credenciales incorrectos";
                } else {
                    echo "Credenciales correctos";
                }
            } else {
                echo "No se han proporcionado datos suficientes";
            }

            return new Response($correcto, Response::HTTP_OK);
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
