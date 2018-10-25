<?php

class PageModel extends Model{
    public function getPage($id) {
        $db = $this->getDB();
        return $db->singleQuery("select * from page where id={$id}");
    }
}