<?php
namespace App\Tests\Form;

use App\Form\AnalyseSojaType;
use App\Entity\AnalyseSoja;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class AnalyseSojaTypeTest extends TypeTestCase
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
        $model = new AnalyseSoja();
        $form = $this->factory->create(AnalyseSojaType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testFormView(): void
    {
        $form = $this->factory->create(AnalyseSojaType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('children', $view->vars);
    }
}
