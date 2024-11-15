<?php
// tests/Form/RegistrationFormTypeTest.php
namespace App\Tests\Form;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class RegistrationFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        $validator = $validatorBuilder->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'plainPassword' => 'securepassword', // Usa il nome corretto del campo password
        ];

        $model = new User();
        $form = $this->factory->create(RegistrationFormType::class, $model);

        // Simula la compilazione del form con i dati
        $form->submit($formData);

        $expected = new User();
        $expected->setEmail('test@example.com');
        $expected->setPassword('securepassword');

        // Verifica che il form sia valido
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected->getEmail(), $model->getEmail());

        // Verifica la struttura del form
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
