<?php

class CategoriesHandler implements HandlerInterface
{
    public function prepare($data)
    {
        $categories = array();

        foreach ($data as $category) {
            $categories[] = array(
                'id' => $category['id'],
                'parentId' => $category['parent_id'],
                'name' => $category['name']
            );
        }

        return $categories;
    }
}