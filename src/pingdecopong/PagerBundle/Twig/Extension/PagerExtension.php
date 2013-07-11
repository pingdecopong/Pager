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

    public function pagesizeRender($pager, $template = null)
    {
        $template = $template ?: 'pingdecopongPagerBundle:Pager:defaultPagesize.html.twig';

        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render($template, $data);
    }

    public function hiddenRender($pager, $template = null)
    {
        $template = $template ?: 'pingdecopongPagerBundle:Pager:defaultHidden.html.twig';

        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render($template, $data);
    }

    public function columnRender($pager, $template = null)
    {
        $template = $template ?: 'pingdecopongPagerBundle:Pager:defaultColumn.html.twig';

        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render($template, $data);
    }

    public function selectorRender($pager, $template = null)
    {
        $template = $template ?: 'pingdecopongPagerBundle:Pager:defaultSelector.html.twig';

        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render($template, $data);
    }

    public function pagerRender($pager, $template = null)
    {
        $template = $template ?: 'pingdecopongPagerBundle:Pager:defaultSelector.html.twig';

        $data = array();
        $data['pager'] = $pager;
        return $this->environment->render($template, $data);
    }

    public function getName()
    {
        return 'pingdecopong_pager';
    }
}