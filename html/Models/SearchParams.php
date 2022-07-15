<?php

Class SearchParams {

    // Returns formed <option> tags containing the appropriate name and value attributes
    function option($name, $value)
    {
        $option = '<option value="'.$value.'"';
        if (isset($_GET[$name])) {
            if ($_GET[$name] == $value) {
                $option = $option.' selected';
            }
        }
        $option = $option.'>'.$value.'</option>';
        echo $option;
    }

    // Returns the ORDER BY syntax for the SQL statement
    public function sort() {
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'Surname [A-Z]':
                    $sort = ' ORDER BY last_name ASC';
                    break;
                case 'Surname [Z-A]':
                    $sort = ' ORDER BY last_name DESC';
                    break;
                case 'First name [A-Z]':
                    $sort = ' ORDER BY first_name ASC';
                    break;
                case 'First name [Z-A]':
                    $sort = ' ORDER BY first_name DESC';
                    break;
                case 'Username [Z-A]':
                    $sort = ' ORDER BY username DESC';
                    break;
                default;
                    $sort = ' ORDER BY username ASC';
            }
        } else {
            $sort = ' ORDER BY username ASC';
        }
        return $sort;
    }
}