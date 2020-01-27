<?php
namespace System;
class Pagination
{
  /**
   * Application Object
   *
   * @var \System\Application
   */

  private $app;
  /**
   * Total items
   *
   * @var int
   */

  private $totalItems;
  /**
   * Items per page
   *
   * @var int
   */

  private $itemsPerPage = 10;
  /**
   * Last page number => total pages
   *
   * @var int
   */

  private $lastPage;
  /**
   * Current page number
   *
   * @var int
   */
  private $page = 1;

  /**
   * Constructor
   *
   * @param \System\Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;

    $this->setCurrentPage();
  }

  /**
   * Set current page
   *
   * @return void
   */
  private function setCurrentPage()
  {
    // ?page=1
    // ?page=2
    // ?page=3
    $page = $this->app->request->get('page');
    // just to make sure that the passed query string parameter page
    // must be number and should be more or equal than 1

    if (!is_numeric($page) or $page < 1) {
      $page = 1;
    }

    $this->page = $page;
  }

  /**
   * Get current page number
   *
   * @return int
   */
  public function page()
  {
    return $this->page;
  }

  /**
   * Get items per page
   *
   * @return int
   */
  public function itemsPerPage()
  {
    return $this->itemsPerPage;
  }

  /**
   * Get total items
   *
   * @return int
   */
  public function totalItems()
  {
    return $this->totalItems;
  }

  /**
   * Get last page
   *
   * @return int
   */
  public function last()
  {
    return $this->lastPage;
  }

  /**
   * Get next page number
   *
   * @return int
   */
  public function next()
  {
    return $this->page + 1;
  }

  /**
   * Get previous page number
   *
   * @return int
   */
  public function prev()
  {
    return $this->page - 1;
  }

  /**
   * Set total items
   *
   * @param int $totalItems
   * @return $this
   */
  public function setTotalItems($totalItems)
  {
    $this->totalItems = $totalItems;

    return $this;
  }

  /**
   * Set items per page
   *
   * @param int $itemsPerPage
   * @return $this
   */
  public function setItemsPerPage($itemsPerPage)
  {
    $this->itemsPerPage = $itemsPerPage;

    return $this;
  }

  /**
   * Start pagination
   *
   * @return $this
   */
  public function paginate()
  {
    $this->setLastPage();

    return $this;
  }

  /**
   * Set last page
   *
   * @return void
   */
  private function setLastPage()
  {
    $this->lastPage = ceil($this->totalItems / $this->itemsPerPage);
  }
}
