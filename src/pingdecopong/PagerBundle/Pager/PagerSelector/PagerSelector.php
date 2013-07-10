<?php


namespace pingdecopong\PagerBundle\Pager\PagerSelector;

use pingdecopong\PagerBundle\Pager\PagerSelector\Form\PagerSelectorFormModel;
use pingdecopong\PagerBundle\Pager\PagerSelector\Form\PagerSelectorFormType;
use Symfony\Component\Form\FormFactory;

class PagerSelector {


    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;
    private $formModel;
    private $formType;
    private $form;
    private $formCreatedFlug;

    private $allCount;
//    private $pageNo;
//    private $pageSize;

    function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->formCreatedFlug = false;

        $this->formModel = new PagerSelectorFormModel();
        $this->formType = new PagerSelectorFormType();
    }

    public function getFormBuilder()
    {
        //基礎フォーム生成
        $formBuilder = $this->formFactory->createBuilder($this->formType, $this->formModel, array('csrf_protection' => false));
        return $formBuilder;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        if(!$this->formCreatedFlug){
            $this->buildForm();
        }
        return $this->form;
    }

    /**
     * @param mixed $allCount
     */
    public function setAllCount($allCount)
    {
        $this->allCount = $allCount;
    }

    /**
     * @return mixed
     */
    public function getAllCount()
    {
        return $this->allCount;
    }

    /**
     * @return \pingdecopong\PagerBundle\Pager\PagerSelector\Form\PagerSelectorFormModel
     */
    public function getFormModel()
    {
        return $this->formModel;
    }

    /**
     * @param mixed $pageNo
     */
    public function setPageNo($pageNo)
    {
//        $this->pageNo = $pageNo;
        $this->formModel->setPageNo($pageNo);
    }

    /**
     * @return mixed
     */
    public function getPageNo()
    {
//        return $this->pageNo;
        return $this->formModel->getPageNo();
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
//        $this->pageSize = $pageSize;
        $this->formModel->setPageSize($pageSize);
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
//        return $this->pageSize;
        return $this->formModel->getPageSize();
    }

    public function setPageSizeList($pageSizeList)
    {
        $this->formType->setSizeList($pageSizeList);
    }
    public function getPageSizeList()
    {
        return $this->formType->getSizeList();
    }

    public function getMaxPageNum()
    {
        $totalNum = (int)($this->allCount/$this->formModel->getPageSize());
        if($this->allCount % $this->formModel->getPageSize() != 0){
            $totalNum++;
        }

        if($totalNum == 0){
            $totalNum = 1;
        }
        return $totalNum;
    }

    private function buildForm()
    {
        //基礎フォーム生成
        $formBuilder = $this->formFactory->createBuilder($this->formType, $this->formModel, array('csrf_protection' => false));
        $this->form = $formBuilder->getForm();

        $this->formCreatedFlug = true;
    }

    private function createPagerNoView()
    {
        $pageNoListView = new PagerSelectorNoView();

        $totalNum = (int)($this->allCount/$this->formModel->getPageSize());
        if($this->allCount % $this->formModel->getPageSize() != 0){
            $totalNum++;
        }

        for($i=0; $i<$totalNum; $i++)
        {
            $row = new PagerSelectorNoRowView();

            //表示
            $row->setLabel($i + 1);

            //ページ番号
            $row->setPageNo($i + 1);

            //選択状態
            if($this->formModel->getPageNo() == $i+1){
                $row->setSelect(true);
            }else{
                $row->setSelect(false);
            }

            $pageNoListView->addRows($row);
        }

        return $pageNoListView;
    }

    private function createPagerSizeView()
    {
        $pageSizeView = new PagerSelectorSizeView();


        return $pageSizeView;
    }

    public function createView()
    {
        $basicPagerView = new PagerSelectorView();

        //pageno
        $pageNoView = $this->createPagerNoView();
        $basicPagerView->setPageNo($pageNoView);

        //pagesize
        $pageSizeView = $this->createPagerSizeView();
        $basicPagerView->setPageSize($pageSizeView);

        return $basicPagerView;
    }

}