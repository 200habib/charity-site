<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationFormTypeTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    // This method runs before each test method
    protected function setUp(): void
    {
        parent::setUp();

        // Accessing the container to get the required services
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->formFactory = self::getContainer()->get(FormFactoryInterface::class);
    }

    public function testFormValidation(): void
    {
        // Create a new User object to pass to the form
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password123');

        // Create the form and pass the User object
        $form = $this->formFactory->create(RegistrationFormType::class, $user);

        // Handle the form submission
        $form->submit([
            'email' => 'test@example.com',
            'password' => 'password123',
            // add any other fields necessary
        ]);

        // Assert the form is valid
        $this->assertTrue($form->isValid());

        // You can add more assertions based on your form's functionality
    }

    public function testSaveUserToDatabase(): void
    {
        // Create a User object and set the email and password
        $user = new User();
        $user->setEmail('newuser@example.com');
        $user->setPassword('newpassword123');

        // Persist the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Assert the user is saved in the database
        $savedUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'newuser@example.com']);
        $this->assertNotNull($savedUser);
        $this->assertSame('newuser@example.com', $savedUser->getEmail());
    }

    public function testFormSubmissionWithInvalidData(): void
    {
        // Create a new User object and a form instance
        $user = new User();
        $form = $this->formFactory->create(RegistrationFormType::class, $user);

        // Submit invalid data (e.g., missing email)
        $form->submit([
            'email' => '',
            'password' => 'password123',
        ]);

        // Assert the form is not valid
        $this->assertFalse($form->isValid());
        // Assert that the error message for email is displayed
        $this->assertTrue($form->get('email')->getErrors()->count() > 0);
    }
}
