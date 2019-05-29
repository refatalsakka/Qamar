<?php

namespace System;

class Pagination
{
    private $app;

    private $totalItems;

    private $itemsPerPage = 10;

    private $lastPage;

    private $page = 1;

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->setCurrentPage();
    }

    private function setCurrentPage()
    {
        $page = $this->app->request->get('page');

        if (! is_numeric($page) || $page < 1) {
            $page = 1;
        }

        $this->page = $page;
    }

    public function page()
    {
        return $this->page;
    }

    public function itemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function totalItems()
    {
        return $this->totalItems;
    }

    public function last()
    {
        return $this->lastPage;
    }

    public function next()
    {
        return $this->page + 1;
    }

    public function prev()
    {
        return $this->page - 1;
    }

    public function setTotalItems($totalItems)
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function paginate()
    {
        $this->setLastPage();

        return $this;
    }

    private function setLastPage()
    {
        $this->lastPage = ceil($this->totalItems / $this->itemsPerPage);
    }

}