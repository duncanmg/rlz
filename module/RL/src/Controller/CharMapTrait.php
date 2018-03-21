<?php

namespace RL\Controller;

use Zend\View\Model\ViewModel;

/**
 * Add a French character map.
 *
 * @author duncan
 */
trait CharMapTrait {

    public function addCharMap(ViewModel $view, $block = 'frenchCharMap') {
        $charMapView = new ViewModel();
        $charMapView->setTemplate('rl/charmap/charmap.phtml');
        return $view->addChild($charMapView, $block);
    }

}
