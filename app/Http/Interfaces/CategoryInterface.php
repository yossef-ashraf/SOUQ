<?php
namespace App\Http\Interfaces;

interface CategoryInterface
{

    public function categoryForAdmin();

    public function categorys($request);

    public function deletecategory($request);

    public function addcategory($request);

    public function updatecategoryByAdmin($request);
}


