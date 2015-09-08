<?php

namespace ItBlaster\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LayoutController extends Controller
{
    /**
     * Шапка сайта
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function headerAction(Request $request)
    {
        return $this->render('ItBlasterMainBundle:Layout:header.html.twig', array());
    }

    /**
     * Футер
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footerAction(Request $request)
    {
        return $this->render('ItBlasterMainBundle:Layout:footer.html.twig', array());
    }

    /**
     * Левое меню
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuLeftAction(Request $request)
    {
        $current_route = $request->get('_route'); //имя текущего пути
        $menu = $this->getMenuLeft($current_route);
        return $this->render('ItBlasterMainBundle:Layout:menu_left.html.twig', array(
            'menu' => $menu
        ));
    }

    /**
     * Пункты левого меню
     *
     * @param $route_name
     * @return array
     */
    private function getMenuLeft($current_route)
    {
        $menu = [
            0 => [
                'path'      => 'projects',
                'title'     => 'Проекты',
                'i_class'   => 'fa fa-fw fa-dashboard',
                'routes'    => ['projects', 'project-show', 'project-add', 'project-edit', 'project-delete', '']
            ],
            1 => [
                'path'      => 'reports',
                'title'     => 'Отчёты',
                'i_class'   => 'fa fa-fw fa-table',
                'routes'    => ['project-link-add', 'project-link-edit', 'project-link-delete', 'project-link-update']
            ]
        ];
        if ($this->UserIsAdmin()) {
            $menu[] = [
                'path'      => 'sonata_admin_dashboard',
                'title'     => 'CMS',
                'i_class'   => 'fa fa-fw fa-wrench',
                'routes'    => []
            ];
        }

        foreach ($menu as $i => $item) {
            $menu[$i]['active'] = in_array($current_route, $menu[$i]['routes']);
        }

        return $menu;
    }

    /**
     * Является ли текущий пользователь админом
     *
     * @return bool
     */
    private function UserIsAdmin()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * Хлебные крошки
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function breadcrumbsAction(Request $request)
    {
        return $this->render('ItBlasterMainBundle:Layout:breadcrumbs.html.twig', array());
    }

    /**
     * Заголовок страницы
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function titleAction(Request $request)
    {
        $title = $this->getTitle($request);
        return $this->render('ItBlasterMainBundle:Layout:title.html.twig', array(
            'title' => $title
        ));
    }

    private function getTitle(Request $request)
    {
        $route_name = $request->get('_route');
        $modules = array(
            'homepage'                          => 'Проверка доступности сайтов',
            'projects'                          => 'Проекты',
            'project-show'                      => 'Проекты',
            'project-add'                       => 'Добавление проекта',
            'project-edit'                      => 'Редактирование проекта',
            'project-link-add'                  => 'Добавление ссылки',
            'project-link-edit'                 => 'Редактирование ссылки',
            'reports'                           => 'Отчёты',
            'fos_user_security_login'           => 'Авторизация',
            'fos_user_registration_register'    => 'Регистрация',
            'fos_user_registration_confirmed'   => 'Успешная регистрация',
            'fos_user_registration_check_email' => 'Вы почти зарегистрировались'
        );
        return isset($modules[$route_name]) ?  $modules[$route_name] : 'CheckSite'; //$this->container->getParameter('project_title');
    }
}
