<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Regex;

class AddCategoryFormType extends AbstractType
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
                    new Length(['min' => 2, 'max' =>30, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'30' ]),
                    new Regex(
                        pattern:'/^[a-zA-Z- \'éèçï]{2,30}$/i',
                        htmlPattern: '^[a-zA-Z- \'éèçï]{2,30}$'
                    )
                ])
            ]
               ])
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '--- Aucun parent---',
                'required'=>false,
                'query_builder'=>function(CategoryRepository $cr){
                    return $cr->createQueryBuilder('c')->orderBy('c.name','ASC');
                }
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
