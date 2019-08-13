<?php

class Bumble_Filter_Block_Widget_Carousel extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{
    protected function _toHtml()
 {

 $url= $_SERVER['HTTP_HOST'];
 $imgload = 'http://'.$url.'/skin/frontend/base/default/bumble_filter/images/loading.gif';

 //RECUPERO DATI
 //slide_by
//interval
//autoplay
//showarrow
//slide_col
//show_dots
 $filter_group = $this->getData('filter_group');
 $use_owl_carousel = $this->getData('use_owl_carousel');

 $slide_by = $this->getData('slide_by');
 $autoplay = $this->getData('autoplay');
 $interval = $this->getData('interval');
 $slide_col = $this->getData('slide_col');
 $show_dots = $this->getData('show_dots');
 $showarrow = $this->getData('showarrow');

//Responsive 1024
 $slide_by_resp_1024 = $this->getData('slide_by_resp_1024');
 $autoplay_resp_1024 = $this->getData('autoplay_resp_1024');
 $interval_resp_1024 = $this->getData('interval_resp_1024');
 $slide_col_resp_1024 = $this->getData('slide_col_resp_1024');
 $show_dots_resp_1024 = $this->getData('show_dots_resp_1024');
 $showarrow_resp_1024 = $this->getData('showarrow_resp_1024');

//Responsive 600
 $slide_by_resp_600 = $this->getData('slide_by_resp_600');
 $autoplay_resp_600 = $this->getData('autoplay_resp_600');
 $interval_resp_600 = $this->getData('interval_resp_600');
 $slide_col_resp_600 = $this->getData('slide_col_resp_600');
 $show_dots_resp_600 = $this->getData('show_dots_resp_600');
 $showarrow_resp_600 = $this->getData('showarrow_resp_600');

//Responsive 480
 $slide_by_resp_480 = $this->getData('slide_by_resp_480');
 $autoplay_resp_480 = $this->getData('autoplay_resp_480');
 $interval_resp_480 = $this->getData('interval_resp_480');
 $slide_col_resp_480 = $this->getData('slide_col_resp_480');
 $show_dots_resp_480 = $this->getData('show_dots_resp_480');
 $showarrow_resp_480 = $this->getData('showarrow_resp_480');

 
 $html =
 '<script>
 
  jQuery(document).ready(function(){
  jQuery(".bumble_carousel").slick({
  infinite: true,
  slidesToShow: '.$slide_col .',
  slidesToScroll: '.$slide_by .',
  autoplay: '.$autoplay .',
  autoplaySpeed: '.$interval .',
  dots:  '.$show_dots .',
  centerMode: true,
  centerPadding:"10px",
  arrows: '.$showarrow .',

  responsive: [
    {
      breakpoint: 1024,
      settings: {
        infinite: true,
        slidesToShow: '.$slide_col_resp_1024 .',
        slidesToScroll: '.$slide_by_resp_1024 .',
        autoplay: '.$autoplay_resp_1024 .',
        autoplaySpeed: '.$interval_resp_1024 .',
        dots:  '.$show_dots_resp_1024 .',
        centerMode: true,
        centerPadding:"10px",
        arrows: '.$showarrow_resp_1024 .',
      }
    },
    {
      breakpoint: 600,
      settings: {
        infinite: true,
        slidesToShow: '.$slide_col_resp_600 .',
        slidesToScroll: '.$slide_by_resp_600 .',
        autoplay: '.$autoplay_resp_600 .',
        autoplaySpeed: '.$interval_resp_600 .',
        dots: '.$show_dots_resp_600.',
        centerMode: true,
        centerPadding:"10px",
        arrows: '.$showarrow_resp_600 .',
      }
    },
    {
      breakpoint: 480,
      settings: {
        infinite: true,
        slidesToShow: '.$slide_col_resp_480 .',
        slidesToScroll: '.$slide_by_resp_480 .',
        autoplay: '.$autoplay_resp_480 .',
        autoplaySpeed: '.$interval_resp_480 .',
        dots:  '.$show_dots_resp_480 .',
        centerMode: true,
        centerPadding:"10px",
        arrows: '.$showarrow_resp_480 .',
      }
    }
   
  ]
  });
});
  
    </script>';
 
$html .= '<div class="bumble_carousel"> ';

$filter = Mage::getModel('bumble_filter/group')->getCollection();
   if($filter_group == 0 || $filter_group == ""){ 
    foreach ($filter as $filters) 
    {
        $res = $filters->getFile();
        $title= $filters->getTitle();
        $filterId= $filters->getFilterId();
        $show= $filters->getStatus();
        $groupIdentifier= Mage::getModel('bumble_filter/filter')->load($filterId)->getIdentifier();
        $identi= $filters->getIdentifier();
        $extension = '.html';
        if($show == 1)
        {
        $html .='<div>
            <a href="http://'.$url.'/'.$groupIdentifier.'/'.$identi.$extension.'"><img data-u="image" class="logo-carousel" src="media/'.$res.'" alt="'.$filters->getTitle().'" />
            </a>
        </div>';
        }

    }
    }
    else 
    {
    foreach ($filter as $filters) 
    {
        $res = $filters->getFile();
        $title= $filters->getTitle();
        $filterId= $filters->getFilterId();
        $show= $filters->getStatus();
        if($filter_group == $filterId){
        $groupIdentifier= Mage::getModel('bumble_filter/filter')->load($filterId)->getIdentifier();
        $identi= $filters->getIdentifier();
        $extension = '.html';

        if($show == 1)
        {
        $html .='<div>
            <a href="http://'.$url.'/'.$groupIdentifier.'/'.$identi.$extension.'"><img data-u="image" class="logo-carousel" src="media/'.$res.'" alt="'.$filters->getTitle().'" />
            </a>
        </div>';
        }
    }

    }
    }

    $html .='</div>';


return $html;
 }
}

   