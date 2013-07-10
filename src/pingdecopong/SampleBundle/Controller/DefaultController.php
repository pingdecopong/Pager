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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * DB,検索フォーム　検索ポストバックタイプ
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
                'sort_enable' => true,
                'db_column_name' => 'id',
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
                'db_column_name' => 'name',
            ))
            ->addColumn('namekana', array(
                'label' => '名称（カナ）',
                'sort_enable' => true,
                'db_column_name' => 'namekana',
            ))
            ->addColumn('created', array(
                'label' => '作成日時',
                'sort_enable' => true,
                'db_column_name' => 'created',
            ));

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);

        if(!$form->isValid())
        {
            return $this->redirect($this->generateUrl('list3'));
        }

        //data
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();

        //db
        $queryBuilder = $this->getDoctrine()
            ->getRepository('pingdecopongSampleBundle:SystemUser')
            ->createQueryBuilder('u');

        //検索
        $data = $form->getData();
        $searchName = $data['search']->getName();
        if(isset($searchName))
        {
            $queryBuilder = $queryBuilder->andWhere('u.name LIKE :name')
                ->setParameter('name', '%'.$searchName.'%');
        }

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //ソート
        $pageSortName = $pager->getSortName();
        $pageSortType = $pager->getSortType();
        if($pageSortName != null && $pageSortType != null)
        {
            $sortColumn = $pager->getColumn($pageSortName);
            $queryBuilder = $queryBuilder->orderBy('u.'.$sortColumn['db_column_name'], $pageSortType);
        }

        //ページング
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        //pager
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

    /**
     * DB,検索フォーム　検索ポストバック
     * @Route("/list4", name="list4")
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function list4Action(Request $request)
    {
        $formFactory = $this->get('form.factory');
        $validator = $this->get('validator');
        $pager = new Pager($formFactory, $validator);

        //
        $pager
            ->addColumn('id', array(
                'label' => 'ID',
                'sort_enable' => true,
                'db_column_name' => 'id',
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
                'db_column_name' => 'name',
            ))
            ->addColumn('namekana', array(
                'label' => '名称（カナ）',
                'sort_enable' => true,
                'db_column_name' => 'namekana',
            ))
            ->addColumn('created', array(
                'label' => '作成日時',
                'sort_enable' => true,
                'db_column_name' => 'created',
            ));

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->getForm();
        $form->bind($request);

        //pager
        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));//list4

        if($request->isMethod('POST') && $form->isValid())
        {
            $queryAllData = $pager->getAllFormQueryStrings();
            $queryPagerData = $pager->getPagerFormQueryKeyStrings();
            $queryAllData[$queryPagerData['pageNo']] = 1;

            return $this->redirect($this->generateUrl('list4', $queryAllData));
        }

        if(($request->isMethod('GET') && !$form->isValid()) || !$pager->isValid())
        {
            return $this->redirect($this->generateUrl('list4'));
        }

        //db
        $queryBuilder = $this->getDoctrine()
            ->getRepository('pingdecopongSampleBundle:SystemUser')
            ->createQueryBuilder('u');

        //検索
        $data = $form->getData();
        //名前
        $searchName = $data['search']->getName();
        if(isset($searchName) && $form['search']['name']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.name LIKE :name')
                ->setParameter('name', '%'.$searchName.'%');
        }
        //カナ
        $searchNameKana = $data['search']->getKana();
        if(isset($searchNameKana) && $form['search']['kana']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.namekana LIKE :namekana')
                ->setParameter('namekana', '%'.$searchNameKana.'%');
        }

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //ソート
        $pageSortName = $pager->getSortName();
        $pageSortType = $pager->getSortType();
        if($pageSortName != null && $pageSortType != null)
        {
            $sortColumn = $pager->getColumn($pageSortName);
            $queryBuilder = $queryBuilder->orderBy('u.'.$sortColumn['db_column_name'], $pageSortType);
        }

        //ページング
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        if($pager->getMaxPageNum() < $pageNo){
            return $this->redirect($this->generateUrl('list4'));
        }
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $entities,
        );
    }

    /**
     * DB,複数検索フォーム　検索ポストバック
     * @Route("/list5", name="list5")
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function list5Action(Request $request)
    {
        $formFactory = $this->get('form.factory');
        $validator = $this->get('validator');
        $pager = new Pager($formFactory, $validator);
        $pager2 = new Pager($formFactory, $validator, 'p2');

        //pager1
        $pager
            ->addColumn('id', array(
                'label' => 'ID',
                'sort_enable' => true,
                'db_column_name' => 'id',
            ))
            ->addColumn('name', array(
                'label' => '名称',
                'sort_enable' => true,
                'db_column_name' => 'name',
            ))
            ->addColumn('namekana', array(
                'label' => '名称（カナ）',
                'sort_enable' => true,
                'db_column_name' => 'namekana',
            ))
            ->addColumn('created', array(
                'label' => '作成日時',
                'sort_enable' => true,
                'db_column_name' => 'created',
            ));

        //pager2
        $pager2
            ->addColumn('id', array(
                'label' => 'ID2',
                'sort_enable' => true,
                'db_column_name' => 'id',
            ))
            ->addColumn('name', array(
                'label' => '名称2',
                'sort_enable' => true,
                'db_column_name' => 'name',
            ))
            ->addColumn('namekana', array(
                'label' => '名称（カナ）2',
                'sort_enable' => true,
                'db_column_name' => 'namekana',
            ))
            ->addColumn('created', array(
                'label' => '作成日時2',
                'sort_enable' => true,
                'db_column_name' => 'created',
            ));

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SearchFormType())
            ->add($pager2->getFormBuilder())
            ->add('search2', new SearchFormType())
            ->getForm();
        $form->bind($request);

        //pager
        $formView = $form->createView();
        //pager1
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));//list4
        //pager2
        $pager2->setAllFormView($formView);
        $pager2->setPagerFormView($formView[$pager2->getFormName()]);
        $pager2->setLinkRouteName($request->get('_route'));//list4

        if($request->isMethod('POST') && $form->isValid())
        {
            $queryAllData = $pager->getAllFormQueryStrings();
            $queryPagerData = $pager->getPagerFormQueryKeyStrings();
            $queryAllData[$queryPagerData['pageNo']] = 1;

            return $this->redirect($this->generateUrl('list5', $queryAllData));
        }

        if(($request->isMethod('GET') && !$form->isValid()) || !$pager->isValid())
        {
            return $this->redirect($this->generateUrl('list5'));
        }

        //db
        $queryBuilder = $this->getDoctrine()
            ->getRepository('pingdecopongSampleBundle:SystemUser')
            ->createQueryBuilder('u');

        //検索
        $data = $form->getData();
        //名前
        $searchName = $data['search']->getName();
        if(isset($searchName) && $form['search']['name']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.name LIKE :name')
                ->setParameter('name', '%'.$searchName.'%');
        }
        //カナ
        $searchNameKana = $data['search']->getKana();
        if(isset($searchNameKana) && $form['search']['kana']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.namekana LIKE :namekana')
                ->setParameter('namekana', '%'.$searchNameKana.'%');
        }

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //ソート
        $pageSortName = $pager->getSortName();
        $pageSortType = $pager->getSortType();
        if($pageSortName != null && $pageSortType != null)
        {
            $sortColumn = $pager->getColumn($pageSortName);
            $queryBuilder = $queryBuilder->orderBy('u.'.$sortColumn['db_column_name'], $pageSortType);
        }

        //ページング
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        if($pager->getMaxPageNum() < $pageNo){
            return $this->redirect($this->generateUrl('list5'));
        }
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $entities,
        );
    }

}
