<?php

namespace App\Http\Controllers;

use App\Models\ConnectionAToken;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use App\Models\ConnectionBToken;
use App\Models\Store;

class ItemController extends Controller
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
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required|max:10',
            'description' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to create item'], 400);
        }

        $user = auth()->user();
        $store = $user->store;

        $itemDetails = $request->only(['name', 'image', 'description', 'quantity', 'price']);
        $itemDetails['owner_id'] = $user->id;
        $itemIns = new Item;
        $storeIns = new Store;
        if($user->region == 'Cairo') {
            $itemIns->setConnection('mysql');
            $storeIns->setConnection('mysql');
            
        } else {
            $itemIns->setConnection('mysql2');
            $storeIns->setConnection('mysql');
            Sanctum::usePersonalAccessTokenModel(ConnectionBToken::class);
        }
        $item = $itemIns->create($itemDetails);

        $item->stores()->attach($store);
        
        if(!$item) {
            return response()->json(['message' => 'failed to create item'], 400);
        } 

        return response()->json(['message' => 'created item successfully', 'item' => $item], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        if(!$item) {
            return response()->json(['message' => 'Item not found!', 404]);
        }
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required|max:10',
            'description' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to edit item'], 400);
        }


        $itemDetails = $request->only(['name', 'image', 'description', 'quantity', 'price']);
        $newItem = $item->update($itemDetails);
        
        if(!$newItem) {
            return response()->json(['message' => 'failed to edit item'], 400);
        } 

        return response()->json(['message' => 'edited item successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
