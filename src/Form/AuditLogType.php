<?php

namespace App\Form;

use App\Entity\AuditLog;
use App\Entity\EmptyJarStat;
use App\Entity\Jar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('createdAt')
            ->add('actionType')
            ->add('jars', EntityType::class, [
                'class' => Jar::class,
                'choice_label' => function($jar) {
                    return '[' . $jar->getId() . '] ' . $jar->getContent();
                },
                'multiple' => true,
                'required' => false,
            ])
            ->add('emptyJarStats', EntityType::class, [
                'class' => EmptyJarStat::class,
                'choice_label' => function($emptyJarStats) {
                    return '[' . $emptyJarStats->getId() . '] ' . $emptyJarStats->getQuantity();
                },
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AuditLog::class,
        ]);
    }
}
