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
      $page_array = explode('=', $url);
      $page_int = end($page_array);  
      return sprintf($this->getAvailablePageWrapperHTML(),$url,$page_int,$page);
  }
  protected $availablePageWrapper  = '<li><a href="javascript:void(0)" url="%s" class="gotopage" page="%s">%s</a></li>';
  
}