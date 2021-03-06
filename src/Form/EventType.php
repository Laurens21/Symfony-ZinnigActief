<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('start')
            ->add('end')
            ->add('min')
            ->add('max')
            ->add('description', CKEditorType::class, array(
                'config' => array(
                    'uiColor'  => '#ffffff'
                ),
            ))
            ->add ('imageFile', VichImageType::class, array(
                'label'             => 'Picture (.jpg or .png)',
                'download_link'     => false,
                'allow_delete'      => false,
                'image_uri'         => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}
