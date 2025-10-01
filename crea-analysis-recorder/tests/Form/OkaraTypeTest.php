<?php
namespace App\Tests\Form;

use App\Form\OkaraType;
use App\Entity\Okara;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class OkaraTypeTest extends TypeTestCase
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
        $model = new Okara();
        $form = $this->factory->create(OkaraType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testFormView(): void
    {
        $form = $this->factory->create(OkaraType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('children', $view->vars);
    }
}
