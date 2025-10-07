<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour CuveCerealesController.
 */
class CuveCerealesControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cuve-cereales/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
    public function testNewPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cuve-cereales/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testShowPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cuve-cereales/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
    public function testEditPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cuve-cereales/1/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testDeleteCuveCereales(): void
    {
        $client = static::createClient();
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete1');
        $client->request('POST', '/cuve-cereales/1', [
            '_token' => $token->getValue(),
        ]);
        $this->assertResponseRedirects('/cuve-cereales/');
    }
}
