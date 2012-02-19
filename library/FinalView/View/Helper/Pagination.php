<?php

/**
* Pagination
*
 * @author dV
*/
class FinalView_View_Helper_Pagination extends Zend_View_Helper_Abstract
{

    private $_base_url;

    public function pagination(Doctrine_Pager_Range $pager_range, $current_page, $base_url = null)
    {
        $pager = $pager_range->getPager();
        $this->_base_url = $base_url;

        if ($pager->getMaxPerPage() < $pager->getNumResults()) {
            $html = '<ul class="pagination">';
            if ($current_page > 1) {
                $html .= '<li class="prev"><a href="' . $this->_makeUrl($current_page - 1) . '">previous</a></li>';
            }
            foreach ($pager_range->rangeAroundPage() as $page) {
                $active = $current_page == $page ? ' class="active"' : '';
                $html .= '<li' . $active . '>' . $page . '</li>';
            }

            if ($current_page < $pager->getLastPage() ) {
                $html .= '<li class="next"><a href="' . $this->_makeUrl($current_page + 1) . '">next</a></li>';
            }

            return $html . '</ul>';
        }

        return '';
    }

    private function _makeUrl($page)
    {

        $request_uri_parts = parse_url($this->_getBaseUrl());
        $path = array_shift($request_uri_parts);
        $query = !empty($request_uri_parts)
            ? array_shift($request_uri_parts)
            : '';

        parse_str($query, $query_array);
        $query = http_build_query(array_merge(
            $query_array, array('page' => $page)));


        return $path . '?' . $query;
    }

    private function _getBaseUrl()
    {
        if (is_null($this->_base_url)) {
            $this->_base_url = Zend_Controller_Front::getInstance()
                ->getRequest()->getRequestUri();
        }

        return $this->_base_url;
    }

}
