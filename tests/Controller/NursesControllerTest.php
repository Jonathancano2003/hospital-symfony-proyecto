<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NursesControllerTest extends WebTestCase
{
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
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

   
    

    public function testShow(): void
    {
        // Usar el último ID para mostrar el enfermero
        $this->client->request('GET', '/nurse/show/' . $this->lastInsertedId);
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        // Usar el último ID para actualizar el enfermero
        $this->client->request(
            'PUT',
            '/nurse/{id}' . $this->lastInsertedId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['user' => 'updated_user', 'pass' => 'new_password123!'])
        );

        $this->assertResponseIsSuccessful();
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        
    }

    public function testDelete(): void
    {
        // Usar el último ID para eliminar el enfermero
        $this->client->request('DELETE', '/nurse/' . $this->lastInsertedId);
        $this->assertResponseIsSuccessful();
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $this->assertStringContainsString('"message":"Nurse deleted successfully"', $responseContent);
    }
}
