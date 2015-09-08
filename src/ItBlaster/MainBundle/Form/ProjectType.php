<?php

namespace ItBlaster\MainBundle\Form;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Форма добавления нового проекта пользователя
 *
 * Class ProjectType
 * @package ItBlaster\MainBundle\Form
 */
class ProjectType extends BaseAbstractType
{
    protected $options = array(
        'name' => 'project',
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
            ->add('email', 'text', array(
                'label'     => 'E-mail для уведомлений',
                'required'  => FALSE,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('link', 'text', array(
                'label'     => 'Ссылка',
                'required'  => TRUE,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('port', 'text', array(
                'label'     => 'Кастомный порт для stage-версии',
                'required'  => FALSE,
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
        ;
    }
}
