<?php

  function paging($page,$num_items,$maxitem,$class="",$attrib="",$pre="",$post="")
  {
    $pre = ($pre==""?'<tr><td colspan="200" style="text-align:center">':$pre);
    $post = ($post==""?'</td></tr>':$post);
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
      $attrib["href"] = "#";
      $attrib["class"] = $class;
      $paging = "";
      $paging .= $pre;
      ($page<=1?$page=1:false);
      $limit = ($page*$maxitem)-$maxitem;
      if(($num_items%$maxitem)==0){
        $lastpage=($num_items/$maxitem);
      }else{
        $lastpage=($num_items/$maxitem)-(($num_items%$maxitem)/$maxitem)+1;
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

  function test_helper_pox()
  {
    return "asdasdasd";
  }