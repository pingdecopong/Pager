<?php

namespace pingdecopong\SampleBundle\Controller;

use pingdecopong\PagerBundle\Pager\Pager;
use pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumn;
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
        $pagerColumn = new PagerColumn($formFactory);

        $pagerColumn
            ->addColumn('id', array(
                'label' => 'ID',
                'sort_enable' => false,
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
            ));

        $pagerSelector->setAllCount(38);

        $pagerSelectorFormBuilder = $pagerSelector->getFormBuilder();
        $pagerColumnFormBuilder = $pagerColumn->getFormBuilder();

        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
            ->add($pagerSelectorFormBuilder)
            ->add($pagerColumnFormBuilder)
            ->getForm();

        $form->bind($request);

        return array(
            'form' => $form->createView(),
            'pager' => $pagerSelector->createView(),
            'column' => $pagerColumn->createView(),
        );
    }

    /**
     * @Route("/list2", name="list2")
     * @Template()
     */
    public function list2Action(Request $request)
    {
        $formFactory = $this->get('form.factory');
        $pager = new Pager($formFactory);

        //
        $pager
            ->addColumn('id', array(
                'label' => 'ID',
                'sort_enable' => false,
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
            ));
        $pager->setAllCount(38);

        //form
        $form = $pager->getFormBuilder()->getForm();
        $form->bind($request);

        return array(
            'form' => $form->createView(),
            'pager' => $pager->createView(),
        );
    }

}
