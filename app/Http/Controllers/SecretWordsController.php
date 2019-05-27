<?php

namespace App\Http\Controllers;

use App\SecretWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\DB;

class SecretWordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * $data->where('created_at', $request->input('date'));
     */
    public function index(Request $request)
    {
        $data = null;
        if($request->has('all') && $request->all==='true'){
            $data = SecretWord::all();
        } else {
            $data = SecretWord::latest()->first();
        }
        
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
        $request->validate([
            'secret' => 'required|string|max:60|min:3'
        ]);
        
        $secretword = SecretWord::findOrFail($id);
        $secretword->secret = $request->secret;
        $secretword->updated_at = $secretword->freshTimestamp(); 

        $secretword->save();
        return response()->json('Secret with ID '.$id.' is edited successfully', '200');

        } catch (Exception $ex) {
            if ($ex->getCode() === 400) {
                return response()->json('Invalid Request. Please provide all the fields.', $ex->getCode());
            } else if ($ex->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $ex->getCode());
            }
            return response()->json('Something went wrong on the server.', $ex->getCode());
        }
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'secret' => 'required|string|max:60|min:3'
        ]);
        return SecretWord::create([
            'secret' => $request->secret
        ]);
    }
}
