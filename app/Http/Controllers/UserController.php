<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:5',
            'name' => 'required',
            'image' => 'required|url',
            'region' => 'required',
            'phone' => 'required|size:11',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to create user'], 400);
        }

        $userIns = new User;
        $userIns->setConnection('mysql');
        $foundUser = $userIns->where(['email'=> $request->email])->first();
        $userIns->setConnection('mysql2');
        $foundUser2 = $userIns->where(['email'=> $request->email])->first();
        if($foundUser || $foundUser2) {
            return response()->json(['message' => 'Email already in use'], 400);
        }

        $userDetails = $request->only(['email', 'name', 'image', 'region', 'phone']);
        $password = Hash::make($request->password);
        $userDetails['password'] = $password;
        $userDetails['balance'] = 0;

        $userIns = new User;
        $storeIns = new Store;
        if($userDetails['region'] == 'Cairo') {
            $userIns->setConnection('mysql');
            $storeIns->setConnection('mysql');
        } else {
            $userIns->setConnection('mysql2');
            $storeIns->setConnection('mysql2');
        }


        $user = $userIns->create($userDetails);
        if(!$user) {
            return response()->json(['message' => 'failed to create user'], 400);
        } 

        $store = $storeIns->create(['name' => $user->name.'\'s Store.', 'user_id' => $user->id]);

        if(!$store) {
            return response()->json(['message' => 'failed to create store'], 400); 
        }

        return response()->json(['message' => 'created user successfully', 'user' => $user], 201);
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // show the user's account and his data.

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        //check email
        $userIns = new User;
        $userIns->setConnection('mysql');
        $user = $userIns->where('email', $fields['email'])->first();
        if(!$user) {
            $userIns->setConnection('mysql2');
            $user = $userIns->where('email', $fields['email'])->first();
        }

        //check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Email or Password is incorrect'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function addBalance(Request $request, User $user) {


        // if(auth()->user()->email != $user->email) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $user = auth()->user();
        $rules = [
            "amount" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to add balance'], 400);
        }

        $user->balance = $user->balance + $request->amount;
        // $userIns = new User;
        // $userIns->setConnection('mysql');
        // $userI = $userIns->where('email', $user->email)->first();
        // if(!$userI) {
        //     $userIns->setConnection('mysql2');
        //     $userI = $userIns->where('email', $userI->email)->first();
        // }

        $user->update();

        return response()->json(['message' => 'balance added succesfully'], 200);




    }
}
