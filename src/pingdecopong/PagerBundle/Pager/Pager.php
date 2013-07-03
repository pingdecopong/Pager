<?php


namespace pingdecopong\PagerBundle\Pager;

use pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumn;
use pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelector;
use Symfony\Component\Form\FormFactory;

class Pager {

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    private $pagerSelector;
    private $pagerColumn;
    private $formView;

    /**
     * @param mixed $formView
     */
    public function setFormView($formView)
    {
        $this->formView = $formView;
    }

    /**
     * @return mixed
     */
    public function getFormView()
    {
        return $this->formView;
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
        $pagerView->setFormView($this->getFormView());

        return $pagerView;
    }


}