<?php

namespace App\Form;


use App\Entity\Keyword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;

class AddKeywordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
              focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
            'label'=>'Nom :',
            'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            'constraints' => [
                new Sequentially([
                    new NotBlank(message: ''),
                    new Length(['min' => 2, 'max' =>50, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'max 50 lettres ' ]),
                    new Regex(
                        pattern:'/^[a-zA-Z- \'éèêàçï]{2,50}$/i',
                        htmlPattern: '^[a-zA-Z- \'éèêàçï]{2,50}$'
                    )
                ])
            ]
               ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Keyword::class,
        ]);
    }
}
