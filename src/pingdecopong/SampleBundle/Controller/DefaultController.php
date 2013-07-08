<?php

namespace pingdecopong\SampleBundle\Controller;

use pingdecopong\PagerBundle\Pager\Pager;
use pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumn;
use pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelector;
use pingdecopong\SampleBundle\Form\Search\SearchFormModel;
use pingdecopong\SampleBundle\Form\Search\SearchFormType;
use pingdecopong\SampleBundle\Form\Search\TestModel;
use pingdecopong\SampleBundle\Form\Search\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
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

        //data
        $data = array();
        for($i=0; $i<100; $i++)
        {
            $data[$i]['id'] = $i;
            $data[$i]['name'] = $i;
        }

        //
        $pager
            ->addColumn('id', array(
                'label' => 'ID',
//                'sort_enable' => false,
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
            ));
        $pager->setAllCount(100);

/*
        $form = $this->createFormBuilder(null, array('csrf_protection' => false))
            ->add('search', new TestType())
            ->getForm();
        $form->bind($request);
//        $form->submit(array());
        $formView = $form->createView();
*/
/*
        $formModel = new TestModel();
        $formType = new TestType();
        $builder = $this->get('form.factory')->createBuilder($formType, $formModel);
        $form = $builder->getForm();
        $formView = $form->createView();
*/
/*
        $form = $this->createFormBuilder(null, array('csrf_protection' => false))
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);
//        $form->submit(array());
        $formView = $form->createView();
*/
/*
        //form
        $form = $formFactory->createBuilder('form', new SearchFormType(), array('csrf_protection' => false))
            ->getForm();
        $form->bind($request);
        $formView = $form->createView();
*/

        //TODO: パラメータ名短縮（pageNoなども含む）
        //TODO: ページャースキーマ
        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);

        //data
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        $viewData = array_slice($data, $pageSize*($pageNo-1), $pageSize);


        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));//list2
/*
        $data = array();
        $a = $this->generateQueryArray($formView, $data);
        $data2 = array();
        $this->getPagerFormQueryNames($formView['form'] ,$data2);


        $e = $formView->vars['value'];
*/
//        for($i=0; $i < 10; $i++)
//        {
//            $d = $form->createView();
//        }

//        $a = $form->getNormData();
//        $b = $form->getViewData();

//        $c = $formView->getIterator();

//        $form = $pager->getFormBuilder()->getForm();
//        $form->bind($request);
//        $a = $this->getFormDataArray($form);
//        $b = $form->getData();
//
//        $formView = $form->createView();
//        $pager->setFormView($formView);

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'datas' => $viewData,
        );
    }

    public function generateQueryArray(FormView $formView, &$queryArray)
    {
        if(count($formView) == 0)
        {
            $queryArray[urlencode($formView->vars['full_name'])] = $formView->vars['value'];
        }else
        {
            foreach($formView as $value)
            {
                $this->generateQueryArray($value, $queryArray);
            }
        }
        return $queryArray;
    }
    public function getPagerFormQueryNames(FormView $pagerFormView, &$queryArray)
    {
        if(count($pagerFormView) == 0)
        {
            $queryArray[$pagerFormView->vars['name']] = urlencode($pagerFormView->vars['full_name']);
        }else
        {
            foreach($pagerFormView as $value)
            {
                $this->getPagerFormQueryNames($value, $queryArray);
            }
        }
        return $queryArray;
    }
/*
    public function generateQueryString(FormView $allFormView, FormView $pagerFormView, $queryString, $pageNo, $pageSize, $sortName, $sortType)
    {
        $query = "";
        if(count($allFormView) == 0)
        {
            $query = $allFormView->vars['full_name'] .'='. $allFormView->vars['value'];
            $queryString .= $query;

        }else
        {
            foreach($allFormView as $value)
            {
            }
        }

    }
*/
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
