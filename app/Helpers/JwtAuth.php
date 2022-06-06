<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth {

    public function __construct() {
        
    }

    public static function signup($email, $password, $getToken = null) {
        //Buscar su existe el usuario con sus credenciales
        $user = User::where('email', $email)->where('password', $password)->first();

        //Comprobar si son correctas
        $signup = false;
        if (is_object($user)) {
            $signup = true;
        }

        //Generar el token con los datos del usuario
        if ($signup) {
            $token = array(
                'sub' => $user->id, //El sub en jwt siempre es el id
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'iat' => time(), // Fecha en la que se ha creado el token
                'exp' => time() + (7 * 24 * 60 * 60) //fecha exp: 1 semana
            );
            $key = 'F=KAg9}nA;(&-?~}';
            $jwt = JWT::encode($token, $key, 'HS256');
            $decoded = JWT::decode($jwt, $key, ['HS256']);
            


            //Devolver los datos decodificados o el token
            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto'
            );
        }
        
        return $data;
    }

    public static function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        $key = 'F=KAg9}nA;(&-?~}';

        
        try {
            //Quitar comillas al token
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }
        
        if($getIdentity){
            return $decoded;
        }

        var_dump($auth);
        return $auth;
        
        var_dump($auth);
    }

}
