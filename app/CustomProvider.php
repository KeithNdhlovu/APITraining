<?php
namespace App;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
// use Auth;

class CustomProvider implements Provider
{
    public function authenticate(Request $request, Route $route)
    {
        $validator = app('validator')->make($request->all(), ['email' => 'required', 'password' => 'required',]);

        if ($validator->fails())
            return (new Response($validator->errors(), 500));


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember')))
        {
            return csrf_tokken();
        }

        throw new UnauthorizedHttpException('Unable to authenticate with supplied username and password.');
    }
}