<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NursesControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        // Crear el cliente simulado
        $client = static::createClient();

        // Realizar una solicitud GET a la página de inicio
        $crawler = $client->request('GET', '/');

        // Verificar que la respuesta es exitosa (código de estado 200)
        $this->assertResponseIsSuccessful();

        // Verificar que el contenido de la etiqueta <h1> contiene el texto "Bienvenido"
        $this->assertSelectorTextContains('h1', 'Bienvenido');
    }
}
