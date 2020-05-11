<?php

namespace App\Http\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class UserService
{
    /**
     * @param $values  datos del modelo a crear
     * @return \App\User
     *
     */
    public static function store($values)
    {
        $user = null;
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' =>  $values['name'],
                'email' => $values['email'],
                'username' => $values['username'],
                'password' => $values['password']
            ]);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            throw $e;
        }
        return $user;
    }


    /**
     * @param $user_id identifica el id del modelo
     * @param $values  datos del modelo a modificar
     * @return \App\User
     *
     */
    public static function update($user_id, $values)
    {
        $user = null;
        try {
            DB::beginTransaction();
            $user = User::findOrFail($user_id);
            if (array_key_exists('password', $values)) {
                $values['password'] =  $values['password'];
                //     $values['password'] =  Hash::make($values['password']);
            }
            $user->update($values);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            throw $e;
        }
        return $user;
    }

    // Iniciar sesión, retorna el token y el status del usuario
    public static function login($values)
    {
        $user = User::with(['roles'])
            ->where('username', $values['username'])
            ->orWhere('email', $values['username'])
            ->first();
        if ($user && Hash::check($values["password"], $user->password)) {
            $tokenResult = $user->createToken('Personal Access Token');
            $user->token = $tokenResult->accessToken;
            return $user;
        }
        abort(401, 'Unauthorized');
    }


    // Iniciar sesión customer, retorna el token y el status del usuario
    public static function loginCustomers($values)
    {
        $user = User::with(['customers', 'roles'])
            ->where('username', $values['username'])
            ->orWhere('email', $values['username'])
            ->first();

        if ($user && Hash::check($values["password"], $user->password) && $user->hasRole('customer')) {
            $tokenResult = $user->createToken('Personal Access Token');
            $user->token = $tokenResult->accessToken;
            return $user;
        }
        abort(401, 'Unauthorized');
    }

    public static function restoreEmail($values)
    {
        $user = null;
        try {
            DB::beginTransaction();
            $user = User::with(['customers', 'roles'])
                ->where('email', $values['email'])
                ->firstOrFail();
            $password = str_random(8);

            $user->password =  $password;
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            throw $e;
        }
        return [$user, $password];
    }


    /**
     * @params ids  Array of notifications id 
     */
    public static function notificationSystem($ids)
    {
        $user = Auth::user();
        $data = $user->notifications()->whereIn('id', $ids)->select('id')->get();
        $data->markAsRead();
        return $data;
    }
}
