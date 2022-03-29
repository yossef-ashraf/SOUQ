<?php
namespace App\Http\Repositories;

use App\Models\category;
use App\Models\department;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\DepartmentInterface;
use Illuminate\Support\Facades\Validator;

class DepartmentRepository implements DepartmentInterface
{
use ApiResponseTrait;

public function departmentForAdmin()
{
if (auth()->user()->auth == 'admin' )
{
$arr=department::get();
return $this->apiResponse(200,"departments",null,$arr);
}
return $this->apiResponse(400,"departments","you not admin");
}

///
public function departments()
{
$arr=department::where('status' , true )->with('category')->get();
return $this->apiResponse(200,"departments",null,$arr);
}

///

public function adddepartment($request)
{
if (auth()->user()->auth == 'admin')
{

$validations = Validator::make($request->all(),[
'name' => 'required|min:3',
'status' => 'required'
]);

if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}
department::create([
'name' => $request->name,
'status' =>  $request->status,
]);
return $this->apiResponse(200, 'department was created');

}
return $this->apiResponse(400,"you not admin ");
}

//
public function updatedepartmentByAdmin($request)
{
if (auth()->user()->auth == 'admin' )
{

$validations = Validator::make($request->all(),[
'id' => 'required|exists:departments,id',
'name' => 'required|min:3',
'status' => 'required'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$department= department::where( 'id' , $request->id )->first();
$department->update([
'id' => $request->id,
'name' => $request->name,
'status' =>  $request->status,
'updated_at	'=> time()
]);

return $this->apiResponse(200, 'department was update');
}
return $this->apiResponse(400, 'department not was update');
}

///

public function deletedepartment($request)
{
if (auth()->user()->auth == 'admin' )
{
$validations = Validator::make($request->all(),[
'id' => 'required|exists:departments,id'
]);
if($validations->fails())
{
return $this->apiResponse(400, 'validation error', $validations->errors());
}

$department= department::where( 'id' , $request->id )->first();
$department->delete();

return $this->apiResponse(200, 'delete department is done');
}
return $this->apiResponse(400,"you not admin ");
}



}
