<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class ContactType
 * @package AppBundle\Form
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, ['attr' => ['class' => 'form-control-lg']])
                ->add('content', TextareaType::class, ['attr' => ['class' => 'form-control-lg']])
                ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn-light btn-lg']]);
    }
}