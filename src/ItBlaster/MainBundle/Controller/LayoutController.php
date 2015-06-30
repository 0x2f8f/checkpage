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
        return $this->render('ItBlasterMainBundle:Layout:menu_left.html.twig', array());
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
            'reports'                           => 'Отчёты',
            'fos_user_security_login'           => 'Авторизация',
            'fos_user_registration_register'    => 'Регистрация',
            'fos_user_registration_confirmed'   => 'Успешная регистрация',
            'fos_user_registration_check_email' => 'Вы почти зарегистрировались'
        );
        return isset($modules[$route_name]) ?  $modules[$route_name] : 'CheckSite'; //$this->container->getParameter('project_title');
    }
}
