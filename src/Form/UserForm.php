<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'label_attr' => [ 'class' => 'form-label'],
                'attr' => [ 'class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('plaintext_password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'label_attr' => [ 'class' => 'form-label'],
                'hash_property_path' => 'password',
                'mapped' => false,
                'attr' => [ 'class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'label_attr' => [ 'class' => 'form-label'],
                'attr' => [ 'class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => true,
                'label_attr' => [ 'class' => 'form-label'],
                'attr' => [ 'class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('profile', EntityType::class, [
                'label' => 'Profile',
                'class' => Profile::class,
                'choice_label' => function (Profile $profile) {
                    return $profile->getRoleName();
                   },
                'required' => true,
                'label_attr' => [ 'class' => 'form-label'],
                'attr' => [ 'class' => 'form-select'],
                'row_attr' => ['class' => 'mb-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
