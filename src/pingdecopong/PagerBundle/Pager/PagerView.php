<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fhirashima
 * Date: 13/07/02
 * Time: 17:10
 * To change this template use File | Settings | File Templates.
 */

namespace pingdecopong\PagerBundle\Pager;


class PagerView {

    /**
     * @var \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorView
     */
    private $pagerSelector;
    /**
     * @var \pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumnView
     */
    private $pagerColumn;
    /**
     * @var \Symfony\Component\Form\FormView
     */
    private $formView;

    /**
     * @var string
     */
    private $routeName;

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param \Symfony\Component\Form\FormView $formView
     */
    public function setFormView($formView)
    {
        $this->formView = $formView;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getFormView()
    {
        return $this->formView;
    }

    /**
     * @param \pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumnView $pagerColumn
     */
    public function setPagerColumn($pagerColumn)
    {
        $this->pagerColumn = $pagerColumn;
    }

    /**
     * @return \pingdecopong\PagerBundle\Pager\PagerColumn\PagerColumnView
     */
    public function getPagerColumn()
    {
        return $this->pagerColumn;
    }

    /**
     * @param \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorView $pagerSelector
     */
    public function setPagerSelector($pagerSelector)
    {
        $this->pagerSelector = $pagerSelector;
    }

    /**
     * @return \pingdecopong\PagerBundle\Pager\PagerSelector\PagerSelectorView
     */
    public function getPagerSelector()
    {
        return $this->pagerSelector;
    }

}