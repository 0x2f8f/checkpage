<?php

namespace ItBlaster\MainBundle\Form;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Форма добавления ссылки проекта
 *
 * Class ProjectLinkType
 * @package ItBlaster\MainBundle\Form
 */
class ProjectLinkType extends BaseAbstractType
{
    protected $options = array(
        'name' => 'project_link',
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label'     => 'Название',
                'required'  => TRUE,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Поле обязательно для заполнения'
                    )),
                )
            ))
            ->add('link', 'text', array(
                'label'     => 'Ссылка',
                'required'  => TRUE,
                'attr' => array(
                    'class' => 'form-control',
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Поле обязательно для заполнения'
                    )),
                )
            ))
        ;
    }
}
