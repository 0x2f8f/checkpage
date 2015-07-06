<?php

namespace ItBlaster\MainBundle\Controller;

use ItBlaster\MainBundle\Model\Project;
use ItBlaster\MainBundle\Model\ProjectLink;
use ItBlaster\MainBundle\Model\ProjectLinkQuery;
use ItBlaster\MainBundle\Service\CheckService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ItBlaster\MainBundle\Controller\traits\ProjectServiceTrait;
use Symfony\Component\HttpFoundation\Response;

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
        $project = $this->getProject($project_name); //ищем проект
        $form = $this->createForm('project_link');
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $project_link = $this->addProjectLink($form, $project);
                $this->addFlash('success','Ссылка успешно создана');

                //редиректим на страницу просмотра проекта
                return $this->redirect($this->generateUrl('project-show', array(
                    'project_name' => $project->getSlug()
                )));
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('ItBlasterMainBundle:ProjectLink:add.html.twig', [
            'project'   => $project,
            'form'      => $form->createView()
        ]);
    }

    //редактирование ссылки
    public function editAction(Request $request, $id)
    {
        $project_link = $this->getProjectLink($id);
        $project = $this->getProjectByLink($project_link);

        $form = $this->createForm('project_link', $project_link);
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                /** @var ProjectLink $project_link */
                $project_link = $form->getData();
                $project_link->save();
                $this->addFlash('success','Ссылка успешно обновлена');

                //редиректим на страницу просмотра проекта
                return $this->redirect($this->generateUrl('project-show', array(
                    'project_name' => $project->getSlug()
                )));
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('ItBlasterMainBundle:ProjectLink:edit.html.twig', [
            'form'      => $form->createView(),
            'project'   => $project,
            'link'      => $project_link
        ]);
    }

    /**
     * Удаление ссылки
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @throws \PropelException
     */
    public function deleteAction(Request $request, $id)
    {
        $project_link = $this->getProjectLink($id);
        $project = $this->getProjectByLink($project_link);
        $project_link->delete();
        $this->addFlash('success','Ссылка успешно удалена');

        //редиректим на страницу просмотра проекта
        return $this->redirect($this->generateUrl('project-show', array(
            'project_name' => $project->getSlug()
        )));
    }

    /**
     * Строчка конкретной ссылки в таблице ссылок проекта
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        if($request->isXmlHttpRequest()){
            $project_link = $this->getProjectLink($id);
            $project = $this->getProjectByLink($project_link);

            $project_link = $this->getCheckService()->updateLink($project_link); //обновляем информацию по ссылке

            $html = $this->renderView('ItBlasterMainBundle:Projects:link_tr.html.twig', array(
                'link' => $project_link,
            ));

            $response = new JsonResponse(array('html'=>$html));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Создание объекта "ссылка проекта" в базе
     *
     * @param $form
     * @param Project $project
     * @return ProjectLink
     * @throws \Exception
     * @throws \PropelException
     */
    private function addProjectLink($form, Project $project)
    {
        $data = $form->getData();
        $link = new ProjectLink();
        $link
            ->setProject($project)
            ->setTitle($data['title'])
            ->setLink($data['link'])
            ->setActive(true)
            ->save();
        return $link;
    }

    /**
     * Объект ссылки проекта
     *
     * @param $link_id
     * @return ProjectLink
     */
    private function getProjectLink($link_id)
    {
        $project_link = ProjectLinkQuery::create()->findOneById($link_id);
        if (!$project_link) {
            throw $this->createNotFoundException('Project link not fonud');
        }
        return $project_link;
    }

    /**
     * Сервис проверки доступности ссылки
     *
     * @return CheckService
     */
    private function getCheckService()
    {
        return $this->container->get('check_service');
    }
}