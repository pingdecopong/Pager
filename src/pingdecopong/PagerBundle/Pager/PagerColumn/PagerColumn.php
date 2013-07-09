<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn;

use pingdecopong\PagerBundle\Pager\PagerColumn\Form\PagerColumnFormModel;
use pingdecopong\PagerBundle\Pager\PagerColumn\Form\PagerColumnFormType;
use Symfony\Component\Form\FormFactory;

class PagerColumn {


    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;
    private $formModel;
    private $formType;
    private $form;
    private $formCreatedFlug;

    private $column;

//    private $sortName;
//    private $sortType;

    function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->formCreatedFlug = false;

        $this->formModel = new PagerColumnFormModel();
        $this->formType = new PagerColumnFormType();

        $this->column = array();
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
     * @return \pingdecopong\PagerBundle\Pager\PagerColumn\Form\PagerColumnFormModel
     */
    public function getFormModel()
    {
        return $this->formModel;
    }

    /**
     * @param mixed $sortName
     */
    public function setSortName($sortName)
    {
//        $this->sortName = $sortName;
        $this->formModel->setSortName($sortName);
    }

    /**
     * @return mixed
     */
    public function getSortName()
    {
//        return $this->sortName;
        return $this->formModel->getSortName();
    }

    /**
     * @param mixed $sortType
     */
    public function setSortType($sortType)
    {
//        $this->sortType = $sortType;
        $this->formModel->setSortType($sortType);
    }

    /**
     * @return mixed
     */
    public function getSortType()
    {
//        return $this->sortType;
        return $this->formModel->getSortType();
    }

    private function buildForm()
    {
        //基礎フォーム生成
        $formBuilder = $this->formFactory->createBuilder($this->formType, $this->formModel, array('csrf_protection' => false));
        $this->form = $formBuilder->getForm();

        $this->formCreatedFlug = true;
    }

    public function addColumn($name, $option)
    {
        $this->column[$name] = $option;
        $this->formModel->addSortNameList($name);
        return $this;
    }

    public function getColumn($name)
    {
        if(!isset($this->column[$name]))
        {
            return false;
        }
        return $this->column[$name];
    }

    public function createView()
    {
        $basicColumnView = new PagerColumnView();

        //
        foreach($this->column as $key => $value)
        {
            $basicColumnRowView = new PagerColumnRowView();

            //key
            $basicColumnRowView->setKeyName($key);

            //表示名
            if(!empty($value['label'])){
                $basicColumnRowView->setLabel($value['label']);
            }else{
                $basicColumnRowView->setLabel($key);
            }

            //sort_enable
            if(isset($value['sort_enable'])){
                $basicColumnRowView->setEnable($value['sort_enable']);
            }else{
                $basicColumnRowView->setEnable(true);
            }

            //ソート選択状態
            if($this->formModel->getSortName() == $key){
                $basicColumnRowView->setSortSelected(true);
                $basicColumnRowView->setSortType($this->formModel->getSortType());
            }else{
                $basicColumnRowView->setSortSelected(false);
            }

            $basicColumnView->addRows($basicColumnRowView);
        }




        return $basicColumnView;
    }


}