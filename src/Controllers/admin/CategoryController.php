<?php

namespace App\Controllers\admin;

use App\Foundation\FCategory;
use App\Models\Category;
use App\Views\admin\VCategory;

class CategoryController {


    public function categoriesAdmin(){
        $fcategories=new FCategory();
        $categories= $fcategories->getAll();
        $vadmin= new VCategory();
        $vadmin->categoriesAdmin($categories);
    }


    function create(){
        $name = $_POST["name"];
        $fcategory = new FCategory();
        $category= new Category();
        if (validate($_POST, [
            "name"=>["minLength:1", "maxLength:20"]
        ])){
            if (!$_FILES["uploadfile"]["error"]==4){
            $category->setName($name);
            $category->setImage(uploadImage());
            $fcategory->store($category);
            redirect(url('/admin/categories'));
            }
            else{
                redirect(url('/admin/categories/add'));
            }
        }else{
            redirect(url('/admin/categories/add'));
        }
    }

    public function showAddCategory(){
        $vadmin= new VCategory();
        $vadmin->showAddCategory();
    }

    public function destroy(){
        $id=$_POST["id"];
        $FCategory = new FCategory();
        $FCategory->delete($id);
        redirect(url("/admin/categories/"));
    }

}