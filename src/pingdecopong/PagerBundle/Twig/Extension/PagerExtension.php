<?php

namespace pingdecopong\PagerBundle\Twig\Extension;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper;
use Symfony\Component\Translation\TranslatorInterface;

class PagerExtension  extends \Twig_Extension
{
    protected $environment;

    public function __construct(RouterHelper $routerHelper, TranslatorInterface $translator)
    {
        $this->routerHelper = $routerHelper;
        $this->translator = $translator;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'pdp_pager_render' => new \Twig_Function_Method($this, 'pagerRender', array('is_safe' => array('html'))),
            'pdp_pager_hidden_render' => new \Twig_Function_Method($this, 'hiddenRender', array('is_safe' => array('html'))),
            'pdp_pager_column_render' => new \Twig_Function_Method($this, 'columnRender', array('is_safe' => array('html'))),
            'pdp_pager_selector_render' => new \Twig_Function_Method($this, 'selectorRender', array('is_safe' => array('html'))),
            'pdp_pager_pagesize_render' => new \Twig_Function_Method($this, 'pagesizeRender', array('is_safe' => array('html'))),
        );
    }

    public function pagesizeRender($pager)
    {
        $data = array();
        $data['pager'] = $pager;
//        $data['pagerformview'] = $pager->getFormView();
        return $this->environment->render('pingdecopongPagerBundle:Pager:defaultPagesize.html.twig', $data);
    }

    public function hiddenRender($pager)
    {
        $data = array();
        $data['pager'] = $pager;
//        $data['pagerformview'] = $pager->getFormView();
        return $this->environment->render('pingdecopongPagerBundle:Pager:defaultHidden.html.twig', $data);
    }

    public function columnRender($pager)
    {
        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render('pingdecopongPagerBundle:Pager:defaultColumn.html.twig', $data);
    }

    public function selectorRender($pager)
    {
        $data = array();
        $data['pager'] = $pager;
//        $data[] = $pagination->getParams();
        return $this->environment->render('pingdecopongPagerBundle:Pager:defaultSelector.html.twig', $data);
    }

    public function pagerRender($pager)
    {
        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render('pingdecopongPagerBundle:Pager:defaultSelector.html.twig', $data);
    }
//    public function pagerRender($pagination, $template = null, array $queryParams = array(), array $viewParams = array())
//    {
//
//    }

    public function getName()
    {
        return 'pingdecopong_pager';
    }
}