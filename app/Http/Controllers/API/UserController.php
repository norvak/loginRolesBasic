<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Http\Requests\SecurityRequest;
use App\Http\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Resources\SecurityResource;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

        /**
     * Login the specified resource in storage.
     * @param  \App\SecurityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(SecurityRequest $request)
    {
        $validated = $request->validated();
        $user = UserService::login($validated);
       
        \Log::debug($user);
        // // if ($user->hasRole('employee')) {
        // //     $this->logLoginDetails($user);
        // // }
        $data = new SecurityResource($user);
        return $data;
    }

    /**
     * logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::user()) {
            $changeLog  = Auth::user();
            $user = Auth::user();
            $user = User::with(['roles'])->where('id', $user->id)->first();
            // if ($user->hasRole('employee')) {
            //     $this->logLoginOff($changeLog);
            // }
            Auth::user()->token()->revoke();
        } else {
            return response()->json([], 404);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'min:4|max:120|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:4|max:120'
        ]);
        $user = User::findOrFail($id);
        $user->disableLogging();
        $user->update(['password' => $request['password']]);

        return response()->json(new UserResource($user), 200);
    }

}
