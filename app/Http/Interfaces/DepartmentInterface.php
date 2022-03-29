<?php
namespace App\Http\Interfaces;

interface DepartmentInterface
{

    public function departmentForAdmin();

    public function departments();

    public function deletedepartment($request);

    public function adddepartment($request);

    public function updatedepartmentByAdmin($request);
}
