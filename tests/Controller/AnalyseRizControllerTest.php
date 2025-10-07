<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel avancé pour AnalyseRizController.
 */
class AnalyseRizControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/analyse-riz/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
    public function testNewPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/analyse-riz/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testShowPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/analyse-riz/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
    public function testEditPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/analyse-riz/1/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
    public function testDeleteAnalyseRiz(): void
    {
        $client = static::createClient();
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete1');
        $client->request('POST', '/analyse-riz/1', [
            '_token' => $token->getValue(),
        ]);
        $this->assertResponseRedirects('/analyse-riz/');
    }
}
