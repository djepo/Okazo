<?php

namespace okazo\annoncesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\TextField As TextField;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('foreignId')
            //->add('foreignTimestamp')
            ->add('titre', 'text', array('required'=>true))
            ->add('texte', 'textarea')
            //->add('lien')
            //->add('websiteCategorie')
            ->add('prix', 'money')
            ->add('codepostal', 'text')
            ->add('ville', 'text')
            //->add('horodatageparse')
            //->add('lastexistencecheck')
            //->add('existe')
            //->add('websiteId')
            //->add('attribut')
            //->add('categorie')
            //->add('city')
            ->add('fosUser')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'okazo\annoncesBundle\Entity\Annonces'
        ));
    }

    public function getName()
    {
        return 'okazo_annoncesbundle_annoncestype';
    }
}
