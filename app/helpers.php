<?php

  function paging($page,$num_items,$display_per_page,$class="",$attrib="",$pre="",$post="")
  {
    $pre = ($pre==""?'':$pre);
    $post = ($post==""?'':$post);
    if($num_items==0){
      $paging = "";
      $paging .= $pre;
        $paging .= '<b style="font-size:200%">No Entries Found.</b>';
      $paging .= $post;
      return $paging;
    }else{
      if($class==""){
        $class = "paging";
      }
      if($attrib==''){
        $attrib = array();
      }
      $attrib["href"] = "javascript:void(0);";
      $attrib["class"] = $class;
      $paging = "";
      $paging .= $pre;
      ($page<=1?$page=1:false);
      $limit = ($page*$display_per_page)-$display_per_page;
      if(($num_items%$display_per_page)==0){
        $lastpage=($num_items/$display_per_page);
      }else{
        $lastpage=($num_items/$display_per_page)-(($num_items%$display_per_page)/$display_per_page)+1;
      }
      $i = 0;
      if(is_array($attrib)){
        foreach ($attrib as $prop => $value) {
          if($i==0){
            $attr = $prop.'="'.$value.'"';
          }else{
            $attr .=" ".$prop.'="'.$value.'"';
          }
          $i++;
        }
      }else{
        $attr = "";
      }
      $maxpage = 3;
      $paging .= '<ul class="pagination prints">';
      $cnt=0;
      if($page>1){
        $back=$page-1;
        $paging .= '<li><a '.$attr.' id="1" data-balloon="FIRST PAGE" data-balloon-pos="down">&laquo;&laquo;</a></li>'; 
        $paging .= '<li><a '.$attr.' id="'.$back.'" data-balloon="PREVIOUS PAGE" data-balloon-pos="down">&laquo;</a></li>'; 
        for($i=($page-$maxpage);$i<$page;$i++){
          if($i>0){
            $paging .= "<li><a $attr id='$i'>$i</a></li>";  
          }
          $cnt++;
          if($cnt==$maxpage){
            break;
          }
        }
      }
      
      $cnt=0;
      for($i=$page;$i<=$lastpage;$i++){
        $cnt++;
        if($i==$page){
          $paging .= '<li class="active '.$class.'-active"><a>'.$i.'</a></li>'; 
        }else{
          $paging .= '<li><a '.$attr.' id="'.$i.'">'.$i.'</a></li>';  
        }
        if($cnt==$maxpage){
          break;
        }
      }
      
      $cnt=0;
      for($i=($page+$maxpage);$i<=$lastpage;$i++){
        $cnt++;
        $paging .= '<li><a '.$attr.' id="'.$i.'">'.$i.'</a></li>';  
        if($cnt==$maxpage){
          break;
        }
      }
      if($page!=$lastpage&&$num_items>0){
        $next=$page+1;
        $paging .= '<li><a '.$attr.' id="'.$next.'" data-balloon="NEXT PAGE" data-balloon-pos="down">&raquo;</a></li>';
        $paging .= '<li><a '.$attr.' id="'.$lastpage.'" data-balloon="LAST PAGE" data-balloon-pos="down">&raquo;&raquo;</a></li>';
      }
      $paging .= '</ul>';

      $paging .= $post;
      return $paging;
    }
  }

  function settlements($type)
  {
    switch ($type) {
      case 'cash':
        return 'Cash';
        break;
      case 'credit':
        return 'Credit Card';
        break;
      case 'debit':
        return 'Debit Card';
        break;
      case 'cheque':
        return 'Cheque';
        break;
      case 'guest_ledger':
        return 'Guest Ledger';
        break;
      case 'send_bill':
        return 'Send Bill';
        break;
      case 'free_of_charge':
        return 'FOC';
        break;
      case 'cancelled':
        return 'Cancelled / Void';
        break;
      case 'bad_order':
        return 'BOD Charge';
        break;
      case 'staff_charge':
        return 'Staff Charge';
        break;
      case 'manager_meals':
        return 'Managers Meal';
        break;
      case 'sales_office':
        return 'Sales Office';
        break;
      case 'representation':
        return 'Representation';
        break;
      default:
        return '';
        break;
    }
  }