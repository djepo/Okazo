<?php

namespace okazo\annoncesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\TextField As TextField;

class ImagesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('file');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'okazo\annoncesBundle\Entity\Images'
        ));
    }

    public function getName() {
        return 'okazo_annoncesbundle_imagestype';
    }

}
