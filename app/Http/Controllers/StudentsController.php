<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentsController extends Controller
{
    /**
     * Display a list of all student ids
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentid()
    {
        $data = Student::all('student_number');
        $studentNumbers = [];
        foreach($data as $dat){
            $studentNumbers[] = $dat->student_number;
        }
        return $studentNumbers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Student::all();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

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
            'student_number' => 'required|size:9|unique:students',
            'ipaddress' => 'required|ipv4'
        ]);
        return Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_number' => $request->student_number,
            'ipaddress' => $request->ipaddress
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

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
        
        $student = Student::findOrFail($id);
        // dd($student);
        $request->validate([
            'first_name' => 'required|string|max:60|min:3',
            'last_name' => 'required|string|max:60|min:3',
            'student_number' => ['required','size:9', 
            Rule::unique('students')->ignore($student->id) ],
            'ipaddress' => 'required|ipv4',
        ]);
        
        
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->student_number = $request->student_number;
        $student->ipaddress = $request->ipaddress;
        $student->updated_at = $student->freshTimestamp(); 

        $student->save();
        return response()->json('Student with ID '.$id.' is edited successfully', 200);

        } catch (Exception $ex) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please provide all the fields.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Student::findOrFail($id)->delete();
            return response()->json('Student with ID '.$id.' is deleted successfully', 200);
        } catch (Exception $ex) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please provide all the fields.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
}
