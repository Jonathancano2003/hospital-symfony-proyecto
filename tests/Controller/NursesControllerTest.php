<?php

namespace App\Tests\Controller;

use App\Entity\Nurses;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NursesControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/nurses/index');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

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
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('DirectInsertUser', $client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        $client = static::createClient();

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
        $this->assertStringContainsString('modified', $client->getResponse()->getContent());
    }

    public function testDelete(): void
    {
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
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('deleted', $client->getResponse()->getContent());
    }
}
