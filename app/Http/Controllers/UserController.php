<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'image' => 'required|url'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to create user'], 400);
        }

        $userDetails = $request->only(['email', 'name', 'image']);
        $password = Hash::make($request->password);
        $userDetails['password'] = $password;
        $user = User::create($userDetails);
        if(!$user) {
            return response()->json(['message' => 'failed to create user'], 400);
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
        //
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
        $user = User::where('email', $fields['email'])->first();

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
    public function getInfo($id){ //el ownerbta3a //not solid //solid
           $user=Item::where('owner_id',$id)
           ->select('name','description','image')
           ->get();
           $query = ['Owner_id' => $id,'creator_id' =>  $id,];   //not solid
           $results = Item::where($query)
           ->select('name','description','image')
           ->get();
           $user1=Item::where('creator_id',$id)
           ->select('name','description','image')
           ->get();
           return response()->json([$user,$results,$user1]) ;
    }
    public function findSearch()
{           
    $search = Input::get ( "search" );       
    $test = Item::where ( 'name', 'LIKE', '%' . $search . '%' );
    if (count ( $test ) > 0)
    return response()->json($test);
    else
    return "No Details found. Try to search again !";       
}
}
