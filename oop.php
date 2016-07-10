<?php

class Tree {

    private $db = null;
    private $category_arr = array();

    public function __construct() {
        //Подключаемся к базе данных, и записываем подключение в переменную _db
        $this->db = new PDO("mysql:host=localhost; dbname=tree", "root", "");
        //В переменную $category_arr записываем все категории (см. ниже)
        $this->category_arr = $this->getCategory();
    }

    private function getCategory() {
        $query = $this->db->query("SELECT * FROM `categories`");
        //Читаем все строчки и записываем в переменную $result
        $result = $query->fetchAll();
        //Перелапачиваем массим (делаем из одномерного массива - двумерный, в котором
        //первый ключ - parent_id)
        $return = array();
        foreach ($result as $value) { //Обходим массив
            $return[$value['parent_id']][] = $value;
        }
        return ($return);
    }

    public function outTree($parent_id, $level) {
        if (isset($this->category_arr[$parent_id])) { //Если категория с таким parent_id существует
            foreach ($this->category_arr[$parent_id] as $value) { //Обходим ее
                /*
                  Выводим категорию
                  $level * 25 - отступ, $level - хранит текущий уровень вложености (0,1,2..)
                 */
                echo "<div style='margin-left:" . ($level * 25) . "px;'>" . $value['name'] . "</div>";
                $level++; //Увеличиваем уровень вложености
                //Рекурсивно вызываем этот же метод, но с новым $parent_id и $level
                $this->outTree($value['id'], $level);
                $level--; //Уменьшаем уровень вложености
            }
        }
    }

}

$tree = new Tree();
$tree->outTree(0, 0); //Выводим дерево
