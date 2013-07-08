<?php


namespace pingdecopong\PagerBundle\Pager;

use pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumn;
use pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelector;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;

class Pager {

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    private $pagerSelector;
    private $pagerColumn;

    private $allFormView;
    private $pagerFormView;

    private $linkRouteName;

    /**
     * @param mixed $linkRouteName
     */
    public function setLinkRouteName($linkRouteName)
    {
        $this->linkRouteName = $linkRouteName;
    }

    /**
     * @return mixed
     */
    public function getLinkRouteName()
    {
        return $this->linkRouteName;
    }

    /**
     * @param mixed $allFormView
     */
    public function setAllFormView($allFormView)
    {
        $this->allFormView = $allFormView;
    }

    /**
     * @return mixed
     */
    public function getAllFormView()
    {
        return $this->allFormView;
    }

    /**
     * @param mixed $pagerFormView
     */
    public function setPagerFormView($pagerFormView)
    {
        $this->pagerFormView = $pagerFormView;
    }

    /**
     * @return mixed
     */
    public function getPagerFormView()
    {
        return $this->pagerFormView;
    }

    public function getPageNo()
    {
        return $this->pagerSelector->getPageNo();
    }
    public function getPageSize()
    {
        return $this->pagerSelector->getPageSize();
    }
    public function getSortName()
    {
        return $this->pagerColumn->getSortName();
    }
    public function getSortType()
    {
        return $this->pagerColumn->getSortType();
    }


    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;

        $this->pagerSelector = new PagerSelector($formFactory);
        $this->pagerColumn = new PagerColumn($formFactory);

    }

    /**
     * @param int $allCount
     */
    public function setAllCount($allCount)
    {
        $this->pagerSelector->setAllCount($allCount);
    }

    /**
     * @return int
     */
    public function getAllCount()
    {
        return $this->pagerSelector->getAllCount();
    }

    public function addColumn($name, $option)
    {
        $this->pagerColumn->addColumn($name, $option);

        return $this;
    }

    public function getColumn($name)
    {
        return $this->pagerColumn->getColumn($name);
    }

    public function getFormName()
    {
        return 'p';
    }

    public function getFormBuilder()
    {
        $pagerSelectorFormBuilder = $this->pagerSelector->getFormBuilder();
        $pagerColumnFormBuilder = $this->pagerColumn->getFormBuilder();

        $formBuilder = $this->formFactory->createNamedBuilder('p', 'form', null, array('csrf_protection' => false))
            ->add($pagerSelectorFormBuilder)
            ->add($pagerColumnFormBuilder);

        return $formBuilder;
    }

    public function createView()
    {
        $pagerView = new PagerView();

        //pagerColumn
        $pagerColumnView = $this->pagerColumn->createView();
        $pagerView->setPagerColumn($pagerColumnView);

        //pagerSelector
        $pagerSelectorView = $this->pagerSelector->createView();
        $pagerView->setPagerSelector($pagerSelectorView);

        //form view
        $pagerView->setFormView($this->getPagerFormView());

        //ページ番号クリック時のリンクパラメータ作成
        $queryAllData = $this->getAllFormQueryStrings();
        $queryPagerData = $this->getPagerFormQueryKeyStrings();
        foreach($pagerView->getPagerSelector()->getPageNo()->getRows() as $value)
        {
            /* @var $value \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorNoRowView */
            $temp = $queryAllData;
            $temp[$queryPagerData['pageNo']] = $value->getPageNo();

            $value->setQuery($temp);
        }

        //カラム名クリック時のリンクパラメータ作成
        foreach($pagerView->getPagerColumn()->getRows() as $value)
        {
            /* @var $value \pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumnRowView */
            $temp = $queryAllData;
            $temp[$queryPagerData['pageNo']] = 1;
            $temp[$queryPagerData['sortName']] = $value->getKeyName();
            if($value->getSortSelected() && $value->getSortType() == 'asc')
            {
                $temp[$queryPagerData['sortType']] = 'desc';
            }else
            {
                $temp[$queryPagerData['sortType']] = 'asc';
            }

            $value->setQuery($temp);
        }

        //route name
        $pagerView->setLinkRouteName($this->linkRouteName);

        return $pagerView;
    }

    /**
     * GETパラメータ用配列取得（全フォーム）
     * @return array
     */
    private function getAllFormQueryStrings()
    {
        $queryAllData = array();
        $this->generateQueryArray($this->allFormView, $queryAllData);
        return $queryAllData;
    }

    /**
     * GETパラメータのKEY用配列取得（ページャー用フォームのみ）
     * @return array
     */
    private function getPagerFormQueryKeyStrings()
    {
        $queryPagerData = array();
        $this->getPagerFormQueryNames($this->pagerFormView ,$queryPagerData);
        return $queryPagerData;
    }

    private function generateQueryArray(FormView $formView, &$queryArray)
    {
        if(count($formView) == 0)
        {
            $queryArray[$formView->vars['full_name']] = $formView->vars['value'];
        }else
        {
            foreach($formView as $value)
            {
                $this->generateQueryArray($value, $queryArray);
            }
        }
        return $queryArray;
    }
    private  function getPagerFormQueryNames(FormView $pagerFormView, &$queryArray)
    {
        if(count($pagerFormView) == 0)
        {
            $queryArray[$pagerFormView->vars['name']] = $pagerFormView->vars['full_name'];
        }else
        {
            foreach($pagerFormView as $value)
            {
                $this->getPagerFormQueryNames($value, $queryArray);
            }
        }
        return $queryArray;
    }

}