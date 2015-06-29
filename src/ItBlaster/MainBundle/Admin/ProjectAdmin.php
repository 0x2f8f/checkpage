<?php

namespace ItBlaster\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjectAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('Id')
            ->add('Title')
            ->add('Active')
            ->add('User')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Id')
            ->addIdentifier('Title')
            ->add('Active', null, [
                'editable' => true,
            ])
            ->add('User')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
//            ->add('Id')
            ->add('Slug', null, array(
                'attr' => array(
                    'maxlength' => 255,
                    'readonly'  => true
                ),
                'help' => 'Максимальная длина 255 символов.'
            ))
            ->add('Title', null,[
                'label' => 'Название'
            ])
            ->add('Active', null,[
                'label' => 'Активен'
            ])
            ->add('User')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
//            ->add('Id')
            ->add('Title')
            ->add('Active')
            ->add('UserId')
        ;
    }
}
