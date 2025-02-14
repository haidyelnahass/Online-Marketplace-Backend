<?php

namespace App\Http\Controllers;

use App\Models\ConnectionAToken;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use App\Models\ConnectionBToken;
use App\Models\Payment;
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
        $items = Item::all();
        if (request('text')) {
            $filteredItems = Item::where('name', 'LIKE', request('text') . '%')->get();
            return response()->json(['items' => $filteredItems]);
        }
        return response()->json(['items' => $items]);
    }

    public function getMyItems()
    {
        $user = auth()->user();
        $itemsCreated = $user->createdItems;
        $itemsOwned = $user->ownedItems;
        return response()->json(['Created Items' => $itemsCreated, 'Owned Items' => $itemsOwned]);
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

        if ($validator->fails()) {
            return response()->json(['message' => 'failed to create item'], 400);
        }

        $user = auth()->user();
        $store = $user->store;

        $itemDetails = $request->only(['name', 'image', 'description', 'quantity', 'price']);
        $itemDetails['creator_id'] = $user->id;
        $itemIns = new Item;
        $storeIns = new Store;
        if ($user->region == 'Cairo') {
            $itemIns->setConnection('mysql');
            $storeIns->setConnection('mysql');
        } else {
            $itemIns->setConnection('mysql2');
            $storeIns->setConnection('mysql2');
        }
        $item = $itemIns->create($itemDetails);

        $item->owners()->attach($user, ['qty' => $item->quantity]);

        $item->stores()->attach($store);

        if (!$item) {
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
        return response()->json(['item' => $item]);
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
        if (!$item) {
            return response()->json(['message' => 'Item not found!', 404]);
        }

        if ($item->creator->id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized!', 401]);
        }
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required|max:10',
            'description' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'failed to edit item'], 400);
        }


        $itemDetails = $request->only(['name', 'image', 'description', 'quantity', 'price']);
        $newItem = $item->update($itemDetails);

        if (!$newItem) {
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


    public function buyItem(Request $request, Item $item)
    {


        if (!$item) {
            return response()->json(['message' => 'Item not found!'], 404);
        }

        

        // if ($item->owner == auth()->user()) {
        //     return response()->json(['message' => 'You cannot buy your own item!'], 400);
        // }

        if ($item->quantity == 0) {
            return response()->json(['message' => 'Item is out of stock!'], 400);
        }

        $user = auth()->user();
        if ($user->balance < $item->price) {
            return response()->json(['message' => 'You don\'t have enough money to buy this!'], 400);
        } else {
            //take money from the user
            $user->balance = $user->balance - $item->price;
            $item->quantity = $item->quantity - 1;
            $user->update();
            //add it to the owner's balance

            $creator = $item->creator;
            $creator->balance = $creator->balance + $item->price;
            $creator->update();

            $item->owners()->attach($user, ['qty' => 1]);
            $item->stores()->sync($user->store);

            $payment = Payment::create([
                'amount' => $item->price,
                'type' => 'Buy Item',
                'item_id' => $item->id,
                'user_id' => $user->id,
                'date' => now(),
            ]);


            return response()->json(['message' => 'Item bought successfully'], 201);
        }
    }

    public function addItem(Request $request, Item $item) {

        if(!$item) {
            return response()->json(['message' => 'no item found'], 400);
        }

        $rules = [
            'quantity' => 'required'
        ];


        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json(['message' => 'failed to add item'], 400);
        }

        $quantity = $request->quantity;




        $creator = $item->creator;

        
        $user = auth()->user();
        if($creator->id == $user->id) {
            return response()->json(['message' => 'cannot add your own item!'], 400);
        }

        
        
        
        $owners = $item->owners;
        
        $currentOwner = $owners->where('id', $user->id)->first();
        if(!$currentOwner) {
            
            $item->owners()->attach($user->id,  ['qty' => $quantity]);
            $item->stores()->attach($user->store->id);
        } else {
            
            foreach($currentOwner->ownedItems as $I) {
                if($I->id == $item->id) {
                    $qty = $I->pivot->qty;
                    $item->owners()->syncWithoutDetaching([$currentOwner->id =>  ['qty' => $qty + $quantity]]);
                }
            }

        }

        $owner = $owners->where('id' , $creator->id)->first();

        foreach($owner->ownedItems as $I) {
            if($I->id == $item->id) {
                $qty = $I->pivot->qty;
                if(($qty - $quantity) <= 0) {
                    $item->owners()->detach($owner);

                } else {
                    
                    $item->owners()->syncWithoutDetaching([$owner->id =>  ['qty' => $qty - $quantity]]);
                }
            }
        }

        return response()->json(['message' => 'added item to store']);
        





    }



}
