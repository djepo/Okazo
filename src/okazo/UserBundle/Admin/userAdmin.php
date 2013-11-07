<?php

namespace okazo\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class userAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('email','text')
            ->add('firstName','text')
            ->add('lastName','text')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper               
            ->add('username')
            ->add('email')
            ->add('firstName', 'doctrine_orm_string')
            ->add('lastName','doctrine_orm_string')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->addIdentifier('email','text')
            ->add('firstName','text')
            ->add('lastName','text')
            ->add('enabled')            
            ->add('locked')
            ->add('last_login','datetime')
            
        ;
    }
}

