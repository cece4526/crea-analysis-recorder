<?php
namespace App\Tests\Form;

use App\Form\AvCorrectCerealesType;
use App\Entity\AvCorrectCereales;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class AvCorrectCerealesTypeTest extends TypeTestCase
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
        $model = new AvCorrectCereales();
        $form = $this->factory->create(AvCorrectCerealesType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testFormView(): void
    {
        $form = $this->factory->create(AvCorrectCerealesType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('children', $view->vars);
    }
}
