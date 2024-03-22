<?php

namespace App\Form;

use App\Entity\MessageData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender')
            ->add('receivedDate', null, [
                'widget' => 'single_text',
            ])
            ->add('content')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MessageData::class,
        ]);
    }
}
