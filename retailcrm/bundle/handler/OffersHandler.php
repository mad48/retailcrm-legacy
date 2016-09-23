<?php

class OffersHandler implements HandlerInterface
{
    public function prepare($offers)
    {
        $categories = $this->getCategories();
        $container = Container::getInstance();

        foreach ($offers as $k => $v) {
            
			$pictures = explode("|", $offers[$k]['picture']);
			foreach ($pictures as $picture) {
				$offers[$k]['pictures'][] = $container->shopUrl . '/files/products/'.$picture;
			}
			unset($offers[$k]['picture']);
			
            $categoryId = $v['categoryId']; 
			$offers[$k]['params']=array(
				array("name"=>"Цвет", "code"=>"color", "value"=>$v['color']),
				array("name"=>"Артикул", "code"=>"article", "value"=>$v['article'])
			);
            $offers[$k]['url'] = $container->shopUrl . '/products' . $categories[$categoryId]['path'] .'/'. $v['url'];
            $offers[$k]['categoryId'] = array($categoryId);
			$offers[$k] = array_filter($offers[$k]);
        }

        return $offers;
    }

    private function getCategories()
    {
        $builder = new ExtendedOffersBuilder();
        $data = $builder->buildCategories();

        $categories = array();
        $process = true;

        foreach($data as $category) {
            $categories[$category['id']] = array(
                'parentId' => $category['parent_id'],
                'path' => $category['url'],
                'name' => $category['name']
            );
        }

        while($process) {
            $count = 0;
            foreach($categories as $k => $v) {
                if ($v['parentId'] != 0) {
                    $categories[$k]['path'] = $categories[$v['parentId']]['path'] .'/'. $v['path'];
                    $categories[$k]['parentId'] = $categories[$v['parentId']]['parentId'];
                    $count++;
                }
            }
            if ($count <= 0) {
                $process = false;
            }
        }

        return $categories;
    }
}