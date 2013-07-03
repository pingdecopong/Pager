<?php

namespace pingdecopong\SampleBundle\Controller;

use pingdecopong\PagerBundle\Pager\Pager;
use pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumn;
use pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelector;
use pingdecopong\SampleBundle\Form\Search\SearchFormModel;
use pingdecopong\SampleBundle\Form\Search\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
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

        $form = $this->createFormBuilder(null, array('csrf_protection' => false))
            ->add('search', new SearchFormType())
            ->getForm();
//        $form->bind($request);
//        $form->submit(array());
        $formView = $form->createView();
/*
        //form
        $form = $formFactory->createBuilder('form', new SearchFormType(), array('csrf_protection' => false))
            ->getForm();
        $form->bind($request);
        $formView = $form->createView();
*/
/*
        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
//            ->add($pager->getFormBuilder())
            ->add('forma', new SearchFormType())
            ->getForm();
        $form->bind($request);
        $formView = $form->createView();
*/
//        $pager->setFormView($formView['form']);

//        $form = $pager->getFormBuilder()->getForm();
//        $form->bind($request);
//        $a = $this->getFormDataArray($form);
//        $b = $form->getData();
//
//        $formView = $form->createView();
//        $pager->setFormView($formView);

        return array(
            'form' => $formView,
//            'pager' => $pager->createView(),
        );
    }

    public function getFormDataArray(Form $form)
    {
        $ret = array();
        $name = $form->getName();

        if($form->count() == 0){
            $ret[$name] = $form->getData();
        }else{
            $temp = array();
            foreach($form as $value)
            {
                $dataArray = $this->getFormDataArray($value);
                $temp = array_merge($temp, $dataArray);
            }
            $ret[$name] = $temp;
        }

        return $ret;
    }

}
