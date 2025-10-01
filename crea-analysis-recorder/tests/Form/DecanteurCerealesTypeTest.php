<?php
namespace App\Tests\Form;

use App\Form\DecanteurCerealesType;
use App\Entity\DecanteurCereales;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class DecanteurCerealesTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return [
            new PreloadedExtension([], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            // Remplir avec des données valides selon les champs du formulaire
        ];
        $model = new DecanteurCereales();
        $form = $this->factory->create(DecanteurCerealesType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testFormView(): void
    {
        $form = $this->factory->create(DecanteurCerealesType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('children', $view->vars);
    }
}
