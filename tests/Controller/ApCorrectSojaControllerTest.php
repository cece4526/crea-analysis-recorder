<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour ApCorrectSojaController.
 */
class ApCorrectSojaControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-soja/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
    public function testNewPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-soja/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testShowPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-soja/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
    public function testEditPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ap-correct-soja/1/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testDeleteApCorrectSoja(): void
    {
        $client = static::createClient();
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete1');
        $client->request('POST', '/ap-correct-soja/1', [
            '_token' => $token->getValue(),
        ]);
        $this->assertResponseRedirects('/ap-correct-soja/');
    }
}
