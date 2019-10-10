<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Hobby;
use Illuminate\Support\Facades\Mail;
use App\Mail\HobbyNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

class HobbyController extends Controller
{

    use SoftDeletes;

    public function create(){
        $user_id = Auth::id();
        $hobby = Hobby::create([
            'user_id' => $user_id,
            'name' => request('name')
        ]);
        
        $user = Auth::user();
        Mail::to($user['email'])->send(new HobbyNotification(
            'create', $user['first_name'], $hobby['name'])
        );

        return response()->json(['hobby' => $hobby], 201);
   }

   public function update(Request $request, $id){
        $hobby = Hobby::findOrFail($id);
        $hobby->update([
            'name' => request('name')
        ]);

        $user = Auth::user();
        Mail::to($user['email'])->send(new HobbyNotification(
            'update', $user['first_name'], $hobby['name'])
        );

        return response()->json(['hobby' => $hobby], 200);
    }

    public function get(Request $request, $id){
        $hobby = Hobby::findOrFail($id);
        return response()->json(['hobby' => $hobby], 200);
    }

    public function delete(Request $request, $id){
        $hobby = Hobby::findOrFail($id);
        $hobby->delete();

        $user = Auth::user();
        Mail::to($user['email'])->send(new HobbyNotification(
            'delete', $user['first_name'], $hobby['name'])
        );

        return response()->json(['message' => 'Hobby deleted successfully'], 200);
    }

    public function getAUserHobbies(){
        $user_id = Auth::id();
        $hobby = Hobby::where('user_id', $user_id)->get();
        return response()->json(['hobbies' => $hobby], 200);
    }
}
