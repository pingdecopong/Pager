<?php


namespace pingdecopong\PagerBundle\Pager\PagerColumn;


class PagerColumnView {

    private $rows;

    function __construct()
    {
        $this->rows = array();
    }

    public function addRows(PagerColumnRowView $row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }

}