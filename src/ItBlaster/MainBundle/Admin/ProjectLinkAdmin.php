<?php

namespace ItBlaster\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjectLinkAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('Id')
            ->add('Title')
            ->add('Link')
            ->add('Active')
            ->add('Project')
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
            ->add('Link')
            ->add('Active', null, [
                'editable' => true,
            ])
            ->add('Project')
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
            ->add('Title',null,[
                'label' => 'Название'
            ])
            ->add('Link',null,[
                'label' => 'Ссылка'
            ])
            ->add('Active', null,[
                'label' => 'Активна'
            ])
            ->add('Project', null, [
                'label' => 'Проект'
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            //->add('Id')
            ->add('Title')
            ->add('Link')
            ->add('Active')
            ->add('Project')
        ;
    }
}
