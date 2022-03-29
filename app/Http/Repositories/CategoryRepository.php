<?php
namespace App\Http\Repositories;

use App\Models\category;
use App\Models\department;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\CategoryInterface;
use Illuminate\Support\Facades\Validator;

class CategoryRepository implements CategoryInterface
{
use ApiResponseTrait;

public function categoryForAdmin()
{
if (auth()->user()->auth == 'admin' )
{
$arr=category::get();
return $this->apiResponse(200,"categorys",null,$arr);
}
return $this->apiResponse(400,"categorys","you not admin");
}


///
public function categorys($request)
{
$validations = Validator::make($request->all(),[
'department_id' => 'required|exists:departments,id'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$arr=category::where([['id', $request->department_id],['status' , true ]])->get();
return $this->apiResponse(200,"categorys",null,$arr);
}

///


public function addcategory($request)
{
if (auth()->user()->auth == 'admin')
{

$validations = Validator::make($request->all(),[
'name' => 'required|min:3',
'status' => 'required'  ,
'department_id' => 'required|exists:departments,id'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
category::create([
'name' => $request->name,
'status' =>  $request->status,
'department_id' =>  $request->department_id
]);
return $this->apiResponse(200, 'account was created');

}
return $this->apiResponse(400,"you not admin ");
}

//
public function updatecategoryByAdmin($request)
{
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:categories,id',
'name' => 'required|min:3',
'status' => 'required'  ,
'department_id' => 'required|exists:departments,id'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$category= category::where( 'id' , $request->id )->first();
$category->update([
'id' => $request->id,
'name' => $request->name,
'status' =>  $request->status,
'department_id' =>  $request->department_id,
'updated_at	'=> time()
]);

return $this->apiResponse(200, 'account was update');
}

}

///

public function deletecategory($request)
{
if (auth()->user()->auth == 'admin' )
{
$validations = Validator::make($request->all(),[
'id' => 'required|exists:categories,id'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$category= category::where( 'id' , $request->id )->first();
$category->delete();

return $this->apiResponse(200, 'delete category is done');
}
return $this->apiResponse(400,"you not admin ");
}



}
