<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour ApCorrectCerealesController.
 */
class ApCorrectCerealesControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-cereales/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
    public function testNewPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-cereales/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testShowPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-cereales/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
    public function testEditPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-cereales/1/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testDeleteApCorrectCereales(): void
    {
        $client = static::createClient();
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete1');
        $client->request('POST', '/ap-correct-cereales/1', [
            '_token' => $token->getValue(),
        ]);
        $this->assertResponseRedirects('/ap-correct-cereales/');
    }
}
