<?php
namespace App;

use Landish\Pagination\Pagination as BasePagination;

class Pagination extends BasePagination {
  
  /**
     * Pagination wrapper HTML.
     *
     * @var string
     */
  protected function getAvailablePageWrapper($url, $page)
  {
      $page_int = $url[strlen($url)-1];
      return sprintf($this->getAvailablePageWrapperHTML(),$url,$page_int,$page);
  }
  protected $availablePageWrapper  = '<li><a href="javascript:void(0)" url="%s" class="gotopage" page="%s">%s</a></li>';
  
}