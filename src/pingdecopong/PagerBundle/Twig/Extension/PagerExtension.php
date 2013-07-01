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
        );
    }

    public function pagerRender()
    {

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