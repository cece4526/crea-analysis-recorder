<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour la gestion des routes non trouvées.
 */
class NotFoundControllerTest extends WebTestCase
{
    public function testNotFoundPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route-inexistante-404');
        $this->assertResponseStatusCodeSame(404);
    }
}
