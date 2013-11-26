<?php

namespace okazo\annoncesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Form\TextField As TextField;

class AnnoncesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre', 'text', array('required' => true,
                    'label' => "Titre de l'annonce",
                    //'help_label_tooltip' => array(  'title' => 'Titre de votre annonce',
                    //                                'content' => 'Choisissez bien le titre de votre annonce.',
                    //                                'placement'=>'left',
                    //                             ),
                    'attr' => array('placeholder' => "Titre de votre annonce",)
                        )
                )
                ->add('texte', 'textarea', array('required' => true,
                    'label' => "Texte de l'annonce",
                    //'help_label_popover' => array(  'title' => "Texte de l'annonce",
                    //                                'content' => 'Choisissez bien le texte de votre annonce. Celui-ci influence notre moteur de recherche.'
                    //                         ),
                    'attr' => array('placeholder' => "Texte de votre annonce",)
                        )
                )
                ->add('prix', 'money', array('required' => true,
                    'label' => "Prix",
                    //'help_label_popover' => array('title' => "Prix de l'annonce",
                    //                                         'content' => 'Etudiez bien les autres annonces similaires afin de ne pas mettre un prix trop élevé.'
                    //                                ),
                    'attr' => array('placeholder' => "Prix de votre annonce",)
                ))
                ->add('codepostal', 'text', array('required' => true,
                    'label' => 'Code Postal'
                        )
                )
                //->add('ville', 'text')
                //->add('attribut')
                //->add('categorie')
                //->add('city')
                //->add('images', 'collection',array('type'=>new ImagesType(), 'allow_add'=>true, 'by_reference'=>false))
                ->add('images', 'collection', array('type' => new ImagesType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'show_legend' => false, // dont show another legend of subform
                    'by_reference' => false,
                    'widget_add_btn' => array('icon' => 'plus',
                        'label' => 'Ajouter Image',
                        'attr' => array('class' => 'btn btn-primary')
                    ),
                    'options' => array(// options for collection fields
                        'label_render' => false,
                        'widget_remove_btn' => array('label' => 'Supprimer',
                            'icon' => 'remove',
                            'attr' => array('class' => 'btn btn-danger')
                        ),
                        'horizontal_input_wrapper_class' => "col-lg-8",
                    )
                        )
                )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'okazo\annoncesBundle\Entity\Annonces'
        ));
    }

    public function getName() {
        return 'okazo_annoncesbundle_annoncestype';
    }

}
