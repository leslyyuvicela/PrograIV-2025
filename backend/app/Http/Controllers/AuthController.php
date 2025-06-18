<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\UpdateBasicProfileRequest;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    function register(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], Response::HTTP_CREATED);
    }

    function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];
        try {
            //Ver si el usuario existe
            if (!User::where('email', $credentials['email'])->exists()) {
                return response()->json(['errors' => ['email' => 'El correo ingresado no está registrado']], Response::HTTP_UNAUTHORIZED);
            }

            if(!$token= JWTAuth::attempt($credentials)){

                return response()->json(['errors' => ['password' => 'Contraseña incorrecta']], Response::HTTP_UNAUTHORIZED);

            }

        } catch (JWTException) {
            return response()->json(['message' => 'Error al crear el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Usuario logueado con éxito',
            'token' => $token,
            'user' => auth()->user()
        ], Response::HTTP_OK);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
        ]);
    }
    public function who(){
        $user = auth()->user();
        return response()->json([
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
        } catch (JWTException $th) {
            return response()->json(['message' => 'No se pudo cerrar la sesión, el token es inválido'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['message' => 'Sesión cerrada con éxito'], Response::HTTP_OK);
    }

    public function UpdateBasicProfile(UpdateBasicProfileRequest $request) {

        $user = auth()->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->surname = $validated['surname'];
        $user->phone = $validated['phone'];
        $user->save();

        return response()->json([
            'message' => 'Datos basicos de perfil actualizados con éxito',
            'user' => $user
        ], 201);


    }

    public function validateEmail(ApiRequest $request)
    {
        $email = $request->input('email');

        if (User::where('email', $email)->exists()) {
            return response()->json(['message' => 'El correo electrónico ya está en uso'], Response::HTTP_CONFLICT);
        }

        return response()->json(['message' => 'El correo electrónico está disponible'], Response::HTTP_OK);
    }
    
}
