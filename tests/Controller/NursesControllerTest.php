<?php

namespace App\Tests\Controller;

use App\Entity\Nurses;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NursesControllerTest extends WebTestCase
{
<<<<<<< HEAD
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nurses/index');

=======
    private KernelBrowser $client;
    private int $lastInsertedId;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->lastInsertedId = $this->getLastInsertedId();
    }

    private function getLastInsertedId(): int
    {
        // Obtener la lista de enfermeros y encontrar el último ID
        $this->client->request('GET', '/nurse/index');
        $nursesList = json_decode($this->client->getResponse()->getContent(), true);

        $lastId = 0;
        foreach ($nursesList as $nurse) {
            if ($nurse['id'] > $lastId) {
                $lastId = $nurse['id'];
            }
        }

        return $lastId;
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/nurse/index');
>>>>>>> d6409850eeea35201c54532e3e474ce7a1d1b812
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

<<<<<<< HEAD
    public function testShow(): void
    {
        $client = static::createClient();

        // Insertar un registro directamente en la base de datos
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $nurse = new Nurses();
        $nurse->setUser('DirectInsertUser');
        $nurse->setPassword('Direct1234!');
        $entityManager->persist($nurse);
        $entityManager->flush();

        // Obtenemos el ID del registro creado
        $id = $nurse->getId();

        // Realizamos una solicitud al endpoint para mostrarlo
        $client->request('GET', '/nurses/show/' . $id);

        // Verificamos la respuesta
=======
   
    

    public function testShow(): void
    {
        // Usar el último ID para mostrar el enfermero
        $this->client->request('GET', '/nurse/show/' . $this->lastInsertedId);
>>>>>>> d6409850eeea35201c54532e3e474ce7a1d1b812
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('DirectInsertUser', $client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
<<<<<<< HEAD
        $client = static::createClient();
=======
        // Usar el último ID para actualizar el enfermero
        $this->client->request(
            'PUT',
            '/nurse/{id}' . $this->lastInsertedId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['user' => 'updated_user', 'pass' => 'new_password123!'])
        );
>>>>>>> d6409850eeea35201c54532e3e474ce7a1d1b812

        // Insertar un registro directamente en la base de datos
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $nurse = new Nurses();
        $nurse->setUser('EditUser');
        $nurse->setPassword('Edit1234!');
        $entityManager->persist($nurse);
        $entityManager->flush();

        // Obtenemos el ID del registro creado
        $id = $nurse->getId();

        // Realizamos una solicitud al endpoint para editarlo
        $client->request(
            'PUT',
            '/nurses/edit/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['user' => 'UpdatedUser', 'pass' => 'Updated1234!'])
        );

        // Verificamos la respuesta
        $this->assertResponseIsSuccessful();
<<<<<<< HEAD
        $this->assertStringContainsString('modified', $client->getResponse()->getContent());
=======
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        
>>>>>>> d6409850eeea35201c54532e3e474ce7a1d1b812
    }

    public function testDelete(): void
    {
<<<<<<< HEAD
        $client = static::createClient();

        // Insertar un registro directamente en la base de datos
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $nurse = new Nurses();
        $nurse->setUser('DeleteUser');
        $nurse->setPassword('Delete1234!');
        $entityManager->persist($nurse);
        $entityManager->flush();

        // Obtenemos el ID del registro creado
        $id = $nurse->getId();

        // Realizamos una solicitud al endpoint para eliminarlo
        $client->request('DELETE', '/nurses/delete/' . $id);

        // Verificamos la respuesta
=======
        // Usar el último ID para eliminar el enfermero
        $this->client->request('DELETE', '/nurse/' . $this->lastInsertedId);
>>>>>>> d6409850eeea35201c54532e3e474ce7a1d1b812
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('deleted', $client->getResponse()->getContent());
    }
}
