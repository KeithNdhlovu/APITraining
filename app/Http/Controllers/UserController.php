<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\UserTransformer;
use App\CustomArraySerializer;
use App\User;
use App\Image;
use App\Book;
use Carbon\Carbon;
use Dingo\Api\Routing\Helpers;
use Mail;
use Auth;
use Storage;
use File;

class UserController extends Controller {
	
	use Helpers;

   /**
   * Create a new password controller instance.
   *
   * @return void
   */
   public function __construct()
   {
     // $this->middleware('auth');
   }

   public function showUser(Request $request, $userID = null) {
      $user = User::find($userID);
      
      if($user)
         return $user;

      return $this->response->errorNotFound();
	}

  public function showImage(Request $request) {
    // $filename = storage_path().'/images/default.jpg';
    // $user = User::find(1);
    // dd($user->image->uuid);
    // return (new Response($user->image, 200));
    // $file = Storage::disk('local')->get('default.jpg');

    // header('Content-Disposition', 'attachment; filename="'.$file.'"');
    // return (new Response($file, 200))
    //           ->header('Content-Type', '');

    $this->uploadImage($request);
  }

  public function uploadImage(Request $request){
    $file = Storage::disk('local')->get('default.jpg');
    $extension = File::mimeType(storage_path().'/app/default.jpg');

    //create a unique id for every upload so thers no conflicts
    $uuid = uniqid();
    $s3 = Storage::disk('local')->put('1/profile/'.$uuid,  $file);

    //Create a bew resource record with matching details
    $image = new Image();
    $image->mime_type = $file->getClientMimeType();
    $image->uuid = $uuid;
    $image->user_id = 1;
    $image->book_id = null;

    $image->save();
  }

   public function showUsers(Request $request) {
      return User::all();
   }
   
   public function doLogin(Request $request) {
      $user = User::where('email', $request->header('email'))->get()->first();
      return (new Response($user->toJson(), 200));
   }

   /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
   public function doRegister(Request $request) {
      $rules = [
         'name' => 'required|min:3',
         'email' => 'required|email|unique:users,email',
         'password' => 'required',
      ];

     $payload = app('request')->only('username', 'password');

     $validator = app('validator')->make($request->all(), $rules);

     if ($validator->fails()) {
         return (new Response($validator->errors(), 500));
     }

     $user = new User ($request->except('password'));
     $user->password = bcrypt($request->password);
     $user->confirmation_code = str_random(32);
     $result = $user->save();

     $data = [
      'confirmation_code' => $user->confirmation_code,
     ];

      // Send them the confirmation code
      // Mail::send('emails.verify', $data, function($message) use($user)
      // {
      //   $message->to($user->email, $user->name)->subject('Email Verification');
      // });

     if (!$user){
       return (new Response('Could not create new user.', $user->errors()));
     }
     
     return (new Response($user->toJson(), 200));
   }

    /**
     * Update the specified resource in storage.
     *
     * @param $userID
     * @return Response
     */
    public function update(Request $request, $userID = null)
    {
      $user = User::find($userID);
      
      if($user){
         $result = $user->update($request->except('password'));

         if(!$result)
            return (new Response('Could not update user.', $user->errors()));

      }

      return $this->response->errorNotFound();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function delete(Request $request, $userID = null)
    {
      $user = User::find($userID);
      
      if(!$user)
         return $this->response->errorNotFound();

      if($user->admin){
         $result = $user->delete();

         if(!$result)
            return (new Response('Could not delete user.', $user->errors()));

      }

      return $this->response->errorUnauthorized();
    }
}
