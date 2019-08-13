<?php
class Bumble_Filter_Block_Prova extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{


protected function _toHtml()
 {
 
 $url= $_SERVER['HTTP_HOST'];

 $title= $this->getData("title");
    $speed= (int)$this->getData("speed") ;
    if($speed != "")
    {
      $speeds= $speed;
    }else{
      $speeds= 3000;
    }

    $html = " <script>
            $(document).ready(function() {
              $('.owl-carousel').owlCarousel({
                rtl:false,
                loop: true,
                margin: 10,
                
                autoplay:true,
                autoplayTimeout:". $speeds.",
                autoplayHoverPause:true,
                responsiveClass: true,
                responsive: { 
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 3,
                    nav: false
                  },
                  1000: {
                    items: 5,
                    nav: true,
                    
                    margin: 20
                  }
                }
              })
            })
          </script>";

if($title != "")
 { 
    $html .= '<div class="carousel-title" ><h2 style="text-align: center;">'. $title.'</h2></div>';
     $html .= '<div class="space" style="border-bottom: 1px solid #E5E4E4;"></div>';

 
 }

   $html .=' <div class="owl-carousel">';

    $filter = Mage::getModel('bumble_filter/filter')->getCollection();

    foreach ($filter as $filters) 
    {
        $res = $filters->getFile();
        $title= $filters->getTitle();
        $id= $filters->getId(); 


        $html .= '
        	<div class="item">
              <a class="dir" href="http://'. $url.'/tryb/filter/index?p='.$id.'"><img data-u="image" src="http://'.$url.'/media/'. $res.'" /></a>
            </div>
            ';
     }

     $html .= '</div>';

return $html;
 }
}
            