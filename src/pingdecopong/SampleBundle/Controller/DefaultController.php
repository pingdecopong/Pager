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

        //TODO: パラメータ名短縮（pageNoなども含む）
        //TODO: ページャースキーマ
        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);

        if(!$form->isValid())
        {
            return $this->redirect($this->generateUrl('list2'));
        }

        //data
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        $viewData = array_slice($data, $pageSize*($pageNo-1), $pageSize);

        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));//list2

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'datas' => $viewData,
        );
    }

    /**
     * @todo ソート機能、検索機能
     * @Route("/list3", name="list3")
     * @Template()
     */
    public function list3Action(Request $request)
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
            ))
            ->addColumn('namekana', array(
                'label' => '名称（カナ）',
                'sort_enable' => true,
            ))
            ->addColumn('created', array(
                'label' => '作成日時',
                'sort_enable' => true,
            ));

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);

        if(!$form->isValid())
        {
            return $this->redirect($this->generateUrl('list2'));
        }

        //data
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();

        //db
        $queryBuilder = $this->getDoctrine()
            ->getRepository('pingdecopongSampleBundle:SystemUser')
            ->createQueryBuilder('u');

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //ページング
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));//list3

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $entities,
        );
    }

}
