<?php
$dbh = mysqli_connect('localhost', 'root', '', 'tree');
$result=mysqli_query($dbh, "SELECT * FROM  categories");
//Если в базе данных есть записи, формируем массив
if   (mysqli_num_rows($result) > 0){
$categories = array();
//В цикле формируем массив разделов, ключом будет id родительской категории, а также массив разделов, ключом будет id категории
while($category =  mysqli_fetch_assoc($result)){
$categories_ID[$category['id']][] = $category;
$categories[$category['parent_id']][$category['id']] =  $category;
}
}

// Вывод дерева

function build_tree($categories,$parent_id,$only_parent = false){
    if(is_array($categories) && isset($categories[$parent_id])){
        $tree = '<ul>';
        if($only_parent==false){
            foreach($categories[$parent_id] as $category){
                $tree .= '<li>'.$category['name'].' #'.$category['id'];
                $tree .=  build_tree($categories,$category['id']);
                $tree .= '</li>';
            }
        }elseif(is_numeric($only_parent)){
            $category = $categories[$parent_id][$only_parent];
            $tree .= '<li>'.$category['name'].' #'.$category['id'];
            $tree .=  build_tree($categories,$category['id']);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
    }
    else return null;
    return $tree;
}


//Нахождение родителя по ID

function find_parent ($categories_ID, $current_id){
    if($categories_ID[$current_id][0]['parent_id']!=0){
        return find_parent($categories_ID,$categories_ID[$current_id][0]['parent_id']);
    }
    return (int)$categories_ID[$current_id][0]['id'];
}
echo build_tree($categories,0);
//
//echo build_tree($categories,0,find_parent($categories_ID,8));
