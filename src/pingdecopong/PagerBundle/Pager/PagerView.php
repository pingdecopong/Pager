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

    private $pagerSelector;
    private $pagerColumn;
    private $formView;

    /**
     * @param mixed $pagerColumn
     */
    public function setPagerColumn($pagerColumn)
    {
        $this->pagerColumn = $pagerColumn;
    }

    /**
     * @return mixed
     */
    public function getPagerColumn()
    {
        return $this->pagerColumn;
    }

    /**
     * @param mixed $pagerSelector
     */
    public function setPagerSelector($pagerSelector)
    {
        $this->pagerSelector = $pagerSelector;
    }

    /**
     * @return mixed
     */
    public function getPagerSelector()
    {
        return $this->pagerSelector;
    }

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

}