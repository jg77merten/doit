<?php

class ImageLibrary_PicnikHandlerController extends Zend_Controller_Action
{

    public function postDispatch()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
    }

    public function popupAction()
    {
        // image path can be with query hash (e.x. media/sitegift/46/image-gallery/moikrug.png?1234)
        // used to avoid image cache
        $src = parse_url($this->getRequest()->getParam('image'), PHP_URL_PATH);

        $image = Doctrine::getTable('PicnikImage')->create(array(
            'user_id' => $this->_helper->user->authorized, // seems it's not used
            'sitegift_id' => $this->getRequest()->getParam('sitegift_id'),
            'image' => $src,
        ));
        $image->save();

        $this->view->img = array
        (
            'src' => $src,
            'id' => $image->id, 
        );

        
		$this->_helper->json(array(
            'popup' => $this->view->render('picnik-handler/popup.phtml'), 
        ));
    }

    public function saveAction()
    {
        $filename = $this->getRequest()->getQuery('file');
        $imageid = $this->getRequest()->getQuery('_imageid');


        $image = Doctrine::getTable('PicnikImage')->find($imageid);
        if (!$data = file_get_contents($filename)) {
            $this->_helper->error->notFound('Can not copy remote file');
        } else {
            file_put_contents(PUBLIC_PATH . '/' . ltrim($image->image, '/'), $data);

            // do not pass object as it deleted at once
            $this->view->sitegift_id = $image->sitegift_id;
            $this->view->image_src = $image->image;

            $image->delete();
        }
    }

}