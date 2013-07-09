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
//    private $formView;

    /**
     * @var \Symfony\Component\Form\FormView
     */
    private $allFormView;

    /**
     * @var \Symfony\Component\Form\FormView
     */
    private $pagerFormView;

    /**
     * @var string
     */
    private $linkRouteName;

    /**
     * @var array
     */
    private $pageSizeParamList;

    function __construct()
    {
        $this->pageSizeParamList = array();
    }

    /**
     * @param $key
     * @param $pageSizeParamList
     */
    public function addPageSizeParamList($key, $pageSizeParamList)
    {
        $this->pageSizeParamList[$key] = $pageSizeParamList;
    }

    /**
     * @return array
     */
    public function getPageSizeParamList()
    {
        return $this->pageSizeParamList;
    }

    /**
     * @param \Symfony\Component\Form\FormView $allFormView
     */
    public function setAllFormView($allFormView)
    {
        $this->allFormView = $allFormView;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getAllFormView()
    {
        return $this->allFormView;
    }

    /**
     * @param \Symfony\Component\Form\FormView $pagerFormView
     */
    public function setPagerFormView($pagerFormView)
    {
        $this->pagerFormView = $pagerFormView;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getPagerFormView()
    {
        return $this->pagerFormView;
    }

    /**
     * @param string $linkRouteName
     */
    public function setLinkRouteName($linkRouteName)
    {
        $this->linkRouteName = $linkRouteName;
    }

    /**
     * @return string
     */
    public function getLinkRouteName()
    {
        return $this->linkRouteName;
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