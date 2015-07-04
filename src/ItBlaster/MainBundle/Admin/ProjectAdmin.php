<?php

namespace ItBlaster\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProjectAdmin extends Admin
{
    private $labels = [
        'title'     => 'Название',
        'active'    => 'Активен',
        'link'      => 'Ссылка',
        'user'      => 'Пользователь',
        'actions'   => 'Действия'
    ];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('Id')
            ->add('Title')
            ->add('Litle')
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
            ->addIdentifier('Title', null, ['label' => $this->labels['title']])
            ->add('Link', null, ['label' => $this->labels['link']])
            ->add('Active', null, [
                'editable'  => true,
                'label'     => $this->labels['active']
            ])
            ->add('User', null, ['label' => $this->labels['user']])
            ->add('_action', 'actions', array(
                'label'     => $this->labels['actions'],
                'actions'   => array(
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
            ->add('Title', null, ['label' => $this->labels['title']])
            ->add('Active', null, ['label' => $this->labels['active']])
            ->add('Link', null, ['label' => $this->labels['link']])
            ->add('User', null, ['label' => $this->labels['user']])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
//            ->add('Id')
            ->add('Title', null, ['label' => $this->labels['title']])
            ->add('Active', null, ['label' => $this->labels['active']])
            ->add('Link', null, ['label' => $this->labels['link']])
            ->add('User', null, ['label' => $this->labels['user']])
        ;
    }
}
