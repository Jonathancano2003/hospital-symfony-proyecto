<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NursesControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/nurses');
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testNew(): void
{
    $this->client->request('POST', '/nurses/new', [
        'nombre' => 'test_user',
        'pass' => 'password123',
    ]);

    $this->assertResponseIsSuccessful();
    $responseContent = $this->client->getResponse()->getContent();
    $this->assertJson($responseContent);
    $this->assertStringContainsString('"Register":"Success"', $responseContent);
}


    public function testShow(): void
    {
        // Asumiendo que existe un enfermero con ID 1 para fines de prueba
        $this->client->request('GET', '/nurses/show/1');
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        // Asumiendo que existe un enfermero con ID 1 para fines de prueba
        $this->client->request('PUT', '/nurses/edit/1', [], [], [], json_encode([
            'user' => 'updated_user',
            'pass' => 'new_password123',
        ]));

        $this->assertResponseIsSuccessful();
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $this->assertStringContainsString('"nurse":"modified"', $responseContent);
    }

    public function testDelete(): void
    {
        // Asumiendo que existe un enfermero con ID 1 para fines de prueba
        $this->client->request('POST', '/nurses/delete/1');
        $this->assertResponseIsSuccessful();
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $this->assertStringContainsString('"message":"Nurse deleted successfully"', $responseContent);
    }
}
