<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour SecurityController.
 */
class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testLogoutRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        $this->assertResponseRedirects('/');
    }
}
