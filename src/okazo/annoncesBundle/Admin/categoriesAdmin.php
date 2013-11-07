<?php

namespace okazo\annoncesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class categoriesAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('libelle', 'text', array('label' => 'Libellé'))
            ->add('weight')
            //->add('categorieParente')
            /*
            ->add(  'categorieParente', 'sonata_category_selector', array(
                    'category' => $this->getSubject() ?: null,
                    'model_manager' => $this->getModelManager(),
                    'class' => $this->getClass(),
                    'required' => false))*/
            ->add('categorieParente', 'entity', array('class' => 'okazo\annoncesBundle\Entity\Categories', 'label'=>'Catégorie parente'))
            ->add('existe')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle')
            //->add('categorieParente')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('libelle')            
            ->add('weight')            
            //->add('categorieParente', 'entity', array('class' => 'okazo\annoncesBundle\Entity\Categories', 'label'=>'Catégorie parente'))
            ->add(  'categorieParente', 'sonata_category_selector', array(
                    'category' => $this->getSubject() ?: null,
                    'model_manager' => $this->getModelManager(),
                    'class' => $this->getClass(),
                    'required' => false))
            ->add('existe','boolean')
        ;
    }
}

