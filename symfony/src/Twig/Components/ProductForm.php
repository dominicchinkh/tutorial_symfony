<?php

namespace App\Twig\Components;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProductForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public ?Product $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ProductType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // This works!
        //   the form will be submitted automatically after this method, now with the new description
        $this->formValues['description'] = 'some auto-generated-description';

        // Submit the form. If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var Product $product */
        $product = $this->getForm()->getData();
        $entityManager->persist($product);
        $entityManager->flush();

        $this->resetForm();

        $this->addFlash('success', 'product saved!');

        return $this->redirectToRoute('template-component', [
        ]);
    }
}
