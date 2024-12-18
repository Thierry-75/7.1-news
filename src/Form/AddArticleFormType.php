<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Keyword;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Sequentially;


class AddArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre',TextType::class,[
            'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
              focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
               dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
            "label"=>"Titre de l'article :",
            'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            'constraints'=>new Sequentially(
                [
                    new NotBlank(message:''),
                    new Length(['min'=>10,'max'=>255,'minMessage'=>'10 caractères','maxMessage'=>'max 255 caractères'])
                ]
            )
               ])
            ->add('contenu',TextareaType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => '', 
                   'rows'=> 10
                   ],
                "label"=>"Contenu de l'article :",
                'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                'constraints'=>[new NotBlank(message:'')]
                ])
            ->add('photos',FileType::class,['attr'=>['class'=>'block w-full text-sm text-gray-900 border 
            border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700
             dark:border-gray-600 dark:placeholder-gray-400','accept'=>'image/jpeg'],
            'label'=>false,
            'multiple'=>true,
            'mapped'=>false,
            'required'=>true,
            'label'=>'Photos d\'illustration',
            'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            
          
        ])
            ->add('category', EntityType::class,['attr'=>[ 'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'],
                'class' => Category::class,
                'label'=>'Catégories :',
                'choice_label' => 'name',
                'multiple' => true,
                'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                'constraints'=>[
                    new NotNull(['message'=>'']),
                    new NotBlank(['message'=>''])
                ]
            ],)
            ->add('keyword', EntityType::class, ['attr'=>['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'],
                'class' => Keyword::class,
                'label'=>'Mots clés :',
                'choice_label' => 'name',
                'multiple' => true,
                'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                'constraints'=>[
                    new NotNull(['message'=>'']),
                    new NotBlank(['message'=>''])
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT,$this->addDate(...))
        ;
    }

    public function addDate(PostSubmitEvent $event){
        $data = $event->getData();
        if(!$data instanceof Article)return;
        $data->setCreateAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
