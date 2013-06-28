<?php

namespace pingdecopong\SampleBundle\Controller;

use pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/list1", name="list1")
     * @Template()
     */
    public function list1Action(Request $request)
    {
        $formFactory = $this->get('form.factory');
        $pagerSelector = new PagerSelector($formFactory);

        $pagerSelector->setAllCount(38);

        $pagerSelectorFormBuilder = $pagerSelector->getFormBuilder();

        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
            ->add($pagerSelectorFormBuilder)
            ->getForm();

        $form->bind($request);

        return array(
            'form' => $form->createView(),
            'pager' => $pagerSelector->createView(),
        );
    }

}
