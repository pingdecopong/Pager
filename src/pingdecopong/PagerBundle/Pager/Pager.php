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

    /**
     * @var int
     */
    private $allCount;

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
//        $this->allCount = $allCount;
    }

    /**
     * @return int
     */
    public function getAllCount()
    {
        return $this->pagerSelector->getAllCount();
//        return $this->allCount;
    }

    public function addColumn($name, $option)
    {
        $this->pagerColumn->addColumn($name, $option);
//        $this->column[$name] = $option;
        return $this;
    }

    public function getFormBuilder()
    {
        $pagerSelectorFormBuilder = $this->pagerSelector->getFormBuilder();
        $pagerColumnFormBuilder = $this->pagerColumn->getFormBuilder();

        $formBuilder = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
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

        //set query param
        $queryAllData = array();
        $this->generateQueryArray($this->getAllFormView(), $queryAllData);
        $queryPagerData = array();
        $this->getPagerFormQueryNames($this->getPagerFormView() ,$queryPagerData);

        return $pagerView;
    }

    private function generateQueryArray(FormView $formView, &$queryArray)
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
    private  function getPagerFormQueryNames(FormView $pagerFormView, &$queryArray)
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

}