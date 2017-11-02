<?php
namespace App;

use Landish\Pagination\Pagination as BasePagination;

class Pagination extends BasePagination {
  
  /**
     * Pagination wrapper HTML.
     *
     * @var string
     */
  protected $availablePageWrapper  = '<li><a href="javascript:void(0)" url="%s" class="gotopage">%s</a></li>';
  
}