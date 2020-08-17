<?php


namespace Code\Model;


interface Pagination
{
    public function getRecordsPerPage();

    public function getPage();

    public function getTotalResults();
}
