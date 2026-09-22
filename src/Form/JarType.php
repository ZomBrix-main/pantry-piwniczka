<?php

namespace App\Form;

use App\Entity\AuditLog;
use App\Entity\Jar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('createdAt')
            ->add('status')
            ->add('location')
            ->add('type')
            ->add('auditLogs', EntityType::class, [
                'class' => AuditLog::class,
                'choice_label' => function($log) {
                    return '[' . $log->getId() . '] ' . $log->getActionType() . ' (' . $log->getCreatedAt()->format('Y-m-d') . ')';
                },
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jar::class,
        ]);
    }
}
