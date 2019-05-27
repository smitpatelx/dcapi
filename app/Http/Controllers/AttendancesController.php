<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AttendancesController extends Controller
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
        $data = (new Attendance)->newQuery();

        if ($request->has('date') && $request->has('time')) {  
            $data->whereDate('created_at', '=', $request->input('date'))
            ->whereTime('created_at', '>=', $request->input('time'));
        } else if ($request->has('date')) {
            $data->whereDate('created_at', '=', $request->input('date'));
        } else if ($request->has('time')) {
            $data->whereTime('created_at', '>=', $request->input('time'));
        }

        return $data->get();
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
            'first_name' => 'required|string|max:60|min:3',
            'last_name' => 'required|string|max:60|min:3',
            'secret' => 'required|string|max:60|min:3',
            'student_number' => 'required|size:9',
            'ipaddress' => 'required|ipv4'
        ]);
        return Attendance::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'secret' => $request->secret,
            'student_number' => $request->student_number,
            'ipaddress' => $request->ipaddress
        ]);
    }
}
