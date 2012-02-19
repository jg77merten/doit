<?php

/**
 * UserTable
 */
class UserTable extends FinalView_Doctrine_Table
{
    protected function emailSelector($email)
    {
        $this->_getQuery()->addWhere($this->getTableName().'.email = ?', $email);
    }

    protected function idSelector($id)
    {
        $this->_getQuery()->addWhere($this->getTableName().'.id = ?', $id);
    }

    protected function roleSelector($role)
    {
        $this->_getQuery()->addWhere($this->getTableName().'.role & ? = ?', array($role, $role));
    }

    protected function idsSelector($ids)
    {
        $this->_getQuery()->andWhereIn($this->getTableName().'.id', empty($ids) ? array(null) : $ids);
    }

    protected function authSelector($auth_object)
    {
        $this->idsSelector($auth_object->id);
    }
}





