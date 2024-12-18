<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Avatar;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class UpdateProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo', TextType::class, [
            'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
              focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
            'label' => 'Pseudo',
            'label_attr' => ['class' => 'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            'constraints' => [
                new Sequentially([
                    new NotBlank(message: ''),
                    new Length(['min' => 6, 'max' => 25, 'minMessage' => '', 'maxMessage' => '']),
                    new Regex(
                        pattern: '/^[a-zA-Zéèêà]{3,20}#[0-9]{2,4}$/i',
                        htmlPattern: '^[a-zA-Zéèêà]{3,20}#[0-9]{2,4}$'
                    )
                ])
            ]
        ])
        ->add('zip', TextType::class, [
            'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
              focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
            'label' => 'Code postal',
            'label_attr' => ['class' => 'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            'constraints' => [
                new Sequentially([
                    new NotBlank(message: ''),
                    new Length(['min' => 5, 'max' => 5, 'minMessage' => '', 'maxMessage' => '']),
                    new Regex(
                        pattern: '/^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$/i',
                        htmlPattern: '^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$'
                    )
                ])
            ]
        ])
        ->add('city', TextType::class, [
            'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
              focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
               'placeholder' => '','accept'=>'image/jpeg, image/webp'],
            'label' => 'Ville',
            'label_attr' => ['class' => 'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400 '],
            'constraints' => [
                new Sequentially([
                    new NotBlank(message: ''),
                    new Length(['min' => 2, 'max' => 30, 'minMessage' => '', 'maxMessage' => '']),
                    new Regex(
                        pattern: '/^[a-zA-Z\' éèêàç]{2,30}$/i',
                        htmlPattern: '^[a-zA-Z\' éèàêç]{2,30}$'

                    )
                ])
            ]
        ])
        ->add('avatar',FileType::class,['attr'=>['class'=>'block w-full text-sm text-gray-900 border 
        border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700
         dark:border-gray-600 dark:placeholder-gray-400'],
        'multiple'=>false,
        'mapped'=>false,
        'required'=>false,
        'label'=>'Avatar',
        'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
    ])

        ->addEventListener(FormEvents::POST_SUBMIT, $this->addDate(...))
    ;
}
public function addDate(PostSubmitEvent $event)
{
    $data = $event->getData();
    if (!($data instanceof User)) return;
    $data->setUpdatedAt(new \DateTimeImmutable());
}
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
    ]);
}
}
