<?php
namespace App\Tests\Form;

use App\Form\ProductionType;
use App\Entity\Production;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductionTypeTest extends TypeTestCase
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
        $model = new Production();
        $form = $this->factory->create(ProductionType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testFormView(): void
    {
        $form = $this->factory->create(ProductionType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('children', $view->vars);
    }
}
