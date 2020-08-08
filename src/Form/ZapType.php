<?php

namespace App\Form;

use App\Entity\PublishableTrait;
use App\Entity\Zap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('subtitle')
            ->add('shortDescription')
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Zap::$typeValues),
            ])
            ->add('duration')
            ->add('link')
            ->add('status', ChoiceType::class, [
                'choices' => array_flip([
                    PublishableTrait::$STATUS_DRAFT => 'status.draft',
                    PublishableTrait::$STATUS_PUBLISHED => 'status.published',
                    PublishableTrait::$STATUS_ARCHIVED => 'status.archived',
                    PublishableTrait::$STATUS_DELETED => 'status.deleted',
                ]),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zap::class,
        ]);
    }
}
