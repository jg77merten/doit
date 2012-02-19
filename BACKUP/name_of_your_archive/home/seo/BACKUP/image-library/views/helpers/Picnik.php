<?php

class ImageLibrary_View_Helper_Picnik extends Zend_View_Helper_Abstract
{

    public function picnik($img_src, $img_id)
    {
        $config = $this->_getConfig();

        $parts = array
        (
            'query' => array
            (
                '_apikey' => $config['apikey'],
                // URL to image
                '_import' => BASE_PATH . ltrim($img_src, '/'),

                // Specifies a destination for the edited image to save
                '_export' => BASE_PATH . ltrim($this->view->url(array(), 'ImageLibraryPicnikHandlerSave'), '/'),
                // the export call will originate directly from the user's browser
                '_export_agent' => 'browser',
                // export button title
                '_export_title' => $config['export_title'],

                // The user is given the option to overwrite the original image or cancel (return to editing in Picnik).
                // If the user chooses "overwrite", the _imageid parameter is passed on at export.
                '_replace' => 'confirm',
                '_imageid' => $img_id,
            ),
        );

        return $this->view->partial('helpers/picnik.phtml', array(
            'src' => http_build_url($config['url'], $parts),
        ));
    }

    private function _getConfig()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('config')->get('picnik');
    }

}