<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel pour AnalyseSojaController.
 *
 * @category Test
 * @package  App\Tests\Controller
 * @author   cece4526 <contact@example.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/cece4526/crea-analysis-recorder
 */
class AnalyseSojaControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/analyse-soja/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testNewPageIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/analyse-soja/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Teste l'affichage d'une fiche AnalyseSoja (show).
     */
    public function testShowPageIsSuccessful(): void
    {
        $client = static::createClient();
        // Création d'une entité en base via le repository ou fixture recommandée en vrai projet
        // Ici, on suppose qu'un id=1 existe
        $crawler = $client->request('GET', '/analyse-soja/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Teste l'accès à la page d'édition (edit).
     */
    public function testEditPageIsSuccessful(): void
    {
        $client = static::createClient();
        // On suppose qu'un id=1 existe
        $crawler = $client->request('GET', '/analyse-soja/1/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Teste la soumission d'un formulaire valide (new).
     */
    public function testSubmitValidForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/analyse-soja/new');
        $form = $crawler->selectButton('Enregistrer')->form([
            // Remplir ici les champs du formulaire selon AnalyseSojaType
            // 'analyse_soja[field]' => 'valeur',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/analyse-soja/');
    }

    /**
     * Teste la suppression d'une entité AnalyseSoja (delete).
     */
    public function testDeleteAnalyseSoja(): void
    {
        $client = static::createClient();
        // On suppose qu'un id=1 existe et qu'un token CSRF est généré
        $crawler = $client->request('GET', '/analyse-soja/1');
        $token = $client->getContainer()->get('security.csrf.token_manager')->getToken('delete1');
        $client->request('POST', '/analyse-soja/1', [
            '_token' => $token->getValue(),
        ]);
        $this->assertResponseRedirects('/analyse-soja/');
    }

    // Vous pouvez ajouter ici d'autres tests si nécessaire
}
