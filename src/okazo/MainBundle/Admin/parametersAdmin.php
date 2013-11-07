<?php

namespace okazo\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class parametersAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('showLogin')            
            ->add('showLanguageChooser')
            ->add('showHeader')
            ->add('useFacebookLogin')
            ->add('useInternalLogin')
            ->add('useGoogleAnalytics')
        ;
    }     

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('showLogin')            
            ->add('showLanguageChooser')
            ->add('showHeader')
            ->add('useFacebookLogin')
            ->add('useInternalLogin')
            ->add('useGoogleAnalytics')
        ;
    }
}

