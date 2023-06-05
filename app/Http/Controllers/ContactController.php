<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
use ApiResponseTrait;

public function AddContact(Request $request)
{
            $validations = Validator::make($request->all(),[
                'lastname' => ['required','min:3'],
                'firstname' => ['required','min:3'],
                'email' => ['required'],
                'message' => ['required','min:3','max:1000'],
            ]);

            if($validations->fails())
            {
            return $this->apiResponse(400, 'validation error', $validations->errors());
            }
            try{

                $Contact= Contact::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'message' => $request->message,

                ]);
                $datalis=[
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'message' => $request->message,
                ];

            Mail::to($request->email)->send(new ContactMail($datalis));

            return $this->apiResponse(200, 'Contact was created');
            } catch (\Exception $th) {
            return $this->apiResponse(400, 'catch error', $th->getMessage() );
            }

}

public function ContactAdmin()
{
        $Contact=Contact::get()->reverse();
        return $this->apiResponse(200,"Contacts",null,$Contact);
}
    //
public function DeleteContact(Request $request)
{
    $validations = Validator::make($request->all(),[
    'id' => 'required|exists:Contacts,id'
    ]);
    if($validations->fails())
    {
    return $this->apiResponse(400, 'validation error', $validations->errors());
    }
    DB::transaction(function()use($request){
    $Contact= Contact::where( 'id' , $request->id )->first();
    $Contact->delete();
    });

    return $this->apiResponse(200, 'delete Contact is done');
}
}
