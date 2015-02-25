<?php
require_once('Model.php');

class CategoryModel extends Model
{
    /*
     * @desc: Category id
     * @expected value: natural number
     */
    public $categoryID; 
    
    /*
     * @desc: Name of the category
     * @@expected value: string
     */
    public $categoryName;
    
    function __construct()
    {
        parent::__construct();
        
        $this->categoryID = 0;
        $this->categoryName = "";
    }
    
    function setCategoryName($val)
    {
        if (!empty($val))
            $this->categoryName = $val;
        else
            $this->categoryName = "";
    }
}