<?php


namespace pingdecopong\PagerBundle\Pager\PagerSelector;


class PagerSelectorNoView {
    private $rows;

    function __construct()
    {
        $this->rows = array();
    }

    /**
     * @param PagerSelectorNoRowView $rows
     */
    public function addRows(PagerSelectorNoRowView $rows)
    {
        $this->rows[] = $rows;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }
}