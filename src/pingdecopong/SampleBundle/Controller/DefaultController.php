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
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/list2", name="list2")
     * @Template()
     */
    public function list2Action(Request $request)
    {
        $formFactory = $this->get('form.factory');
        $validator = $this->get('validator');
        $pager = new Pager($formFactory, $validator);

        //data
        $entities = array();
        for($i=0; $i<100; $i++)
        {
            $entities[$i]['id'] = $i;
            $entities[$i]['name'] = $i;
        }
        $pager->setAllCount(100);

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

        $form = $pager->getFormBuilder()
            ->getForm();
        $form->bind($request);

        //pager
        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView);
        $pager->setLinkRouteName($request->get('_route'));//list2

        if(!$form->isValid())
        {
            return $this->redirect($this->generateUrl('list2'));
        }

        //data
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        $viewData = array_slice($entities, $pageSize*($pageNo-1), $pageSize);

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $viewData,
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
        $pager->setLinkRouteName($request->get('_route'));//list5
        //pager2
        $pager2->setAllFormView($formView);
        $pager2->setPagerFormView($formView[$pager2->getFormName()]);
        $pager2->setLinkRouteName($request->get('_route'));//list5

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

        //db2
        $queryBuilder2 = $this->getDoctrine()
            ->getRepository('pingdecopongSampleBundle:SystemUser2')
            ->createQueryBuilder('u2');

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

        //検索2
        //名前2
        $searchName2 = $data['search2']->getName();
        if(isset($searchName2) && $form['search2']['name']->isValid())
        {
            $queryBuilder2 = $queryBuilder2->andWhere('u2.name LIKE :name')
                ->setParameter('name', '%'.$searchName2.'%');
        }
        //カナ2
        $searchNameKana2 = $data['search2']->getKana();
        if(isset($searchNameKana2) && $form['search2']['kana']->isValid())
        {
            $queryBuilder2 = $queryBuilder2->andWhere('u2.namekana LIKE :namekana')
                ->setParameter('namekana', '%'.$searchNameKana2.'%');
        }

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //全件数取得2
        $queryBuilderCount2 = clone $queryBuilder2;
        $queryBuilderCount2 = $queryBuilderCount2->select('count(u2.id)');
        $queryCount2 = $queryBuilderCount2->getQuery();
        $allCount2 = $queryCount2->getSingleScalarResult();
        $pager2->setAllCount($allCount2);

        //ソート
        $pageSortName = $pager->getSortName();
        $pageSortType = $pager->getSortType();
        if($pageSortName != null && $pageSortType != null)
        {
            $sortColumn = $pager->getColumn($pageSortName);
            $queryBuilder = $queryBuilder->orderBy('u.'.$sortColumn['db_column_name'], $pageSortType);
        }

        //ソート2
        $pageSortName2 = $pager2->getSortName();
        $pageSortType2 = $pager2->getSortType();
        if($pageSortName2 != null && $pageSortType2 != null)
        {
            $sortColumn2 = $pager2->getColumn($pageSortName2);
            $queryBuilder2 = $queryBuilder2->orderBy('u2.'.$sortColumn2['db_column_name'], $pageSortType2);
        }

        //ページング
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        if($pager->getMaxPageNum() < $pageNo){
            return $this->redirect($this->generateUrl('list5'));
        }
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //ページング2
        $pageSize2 = $pager2->getPageSize();
        $pageNo2 = $pager2->getPageNo();
        if($pager2->getMaxPageNum() < $pageNo2){
            return $this->redirect($this->generateUrl('list5'));
        }
        $queryBuilder2 = $queryBuilder2->setFirstResult($pageSize2*($pageNo2-1))
            ->setMaxResults($pageSize2);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        //クエリー実行2
        $entities2 = $queryBuilder2->getQuery()->getResult();

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'pager2' => $pager2->createView(),
            'entities' => $entities,
            'entities2' => $entities2,
        );
    }

}
