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
            ->add('UserId')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
//            ->add('Id')
            ->add('Title')
            ->add('Active')
            ->add('UserId')
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
            ->add('Title', null,[
                'label' => 'Название'
            ])
            ->add('Active', null,[
                'label' => 'Активен'
            ])
            ->add('UserId', 'model', [
                'class' => 'FOS\\UserBundle\\Propel\\User',
                'label' => 'Пользователь',
                'required' => true,
                //'data' => SolutionQuery::create()->findOneById($defaults['Solution']),
            ])
            //->add('UserId')
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
