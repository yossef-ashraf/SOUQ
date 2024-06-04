<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function show()
    {
     $Contact= Contact::get();
     return $this->apiResponse(200,__('lang.Successfully'),null,$Contact);
    }
    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:contacts,id',
        ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        // قم بتطبيق قواعد التحقق هنا إن لزم الأمر
        $Contact = Contact::findOrFail($request->id);
        return $this->apiResponse(200,__('lang.Successfully'),null,$Contact);
    }
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:contacts,id',
            ]);
        if ($validator->fails()) {
        return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }
        $Contact = Contact::findOrFail($request->id);
        $Contact->delete();

        return $this->apiResponse(200, __('lang.Successfully'));
    }
}
