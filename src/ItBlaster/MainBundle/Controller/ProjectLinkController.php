<?php

namespace ItBlaster\MainBundle\Controller;

use ItBlaster\MainBundle\Model\ProjectLink;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use ItBlaster\MainBundle\Controller\traits\ProjectServiceTrait;

class ProjectLinkController extends Controller
{
    use ProjectServiceTrait;

    /**
     * Добавление ссылки проекта
     *
     * @param Request $request
     * @param $project_name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, $project_name)
    {
        $form = $this->createForm('project_link');
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $project = $this->addProjectLink($form);

                //редиректим на страницу просмотра проекта
                return $this->redirect($this->generateUrl('project-show', array(
                    'project_name' => $project->getSlug()
                )));
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('ItBlasterMainBundle:Projects:add.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    //редактирование ссылки
    public function editAction(Request $request, $id)
    {

    }

    //удаление ссылки
    public function deleteAction(Request $request, $id)
    {

    }

    //Создание объекта "ссылка проекта" в базе
    private function addProjectLink($form)
    {
        $data = $form->getData();
        $link = new ProjectLink();
    }
}