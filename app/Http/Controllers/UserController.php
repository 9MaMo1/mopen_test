<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  private $user;

    public function __construct(){

        $this->user = new User();

      }



    function userLogin(Request $request) {

        $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required',Rule::in(['user', 'admin'])],
       ]);

       $username = request('email');
       $password = request('password');

         $request->request->add([
           'grant_type'    => 'password',
           'client_id'     => env('CLIENT_ID'),
           'client_secret' => env('CLIENT_SECRET'),
           'username'      => $username,
           'password'      => $password,
           'scope'         => null,
       ]);

       // Fire off the internal request.
       $proxy = Request::create(
           'oauth/token',
           'POST'
       );

       $content = \Route::dispatch($proxy);


       $content = $content->getContent();

       $data = json_decode($content, TRUE);

       if(isset($data['token_type'] ) && $data['token_type'] == "Bearer") {

         return response()->json(['data' => $data,'success'=> 1 ] ,  200);

       } elseif(isset($data['success'] ) && $data['success'] == false) {

          return response()->json([ 'data'=> null ,'success'=> 0 ,'error' => 'Unauthenticated.'], 401);

       }



   }
}
