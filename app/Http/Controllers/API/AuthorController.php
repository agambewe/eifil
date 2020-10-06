<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Author;
use Illuminate\Http\Request;
use Response;
use Validator;

class AuthorController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function show()
    {
        return Author::all();
    }
    
    public function find($id){
        $data = Author::find($id);

        if (is_null($data)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Success!";
            $res['data'] = $data;
            return response($res);
        }
    }

    public function store(Request $request)
    {
        $input_data = $request->all();

        $validation = Validator::make($input_data, [
            'name' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }
        
        $author = new Author();     
        $author->name = $input_data['name'];

        if($author->save()){
            $res['message'] = "Successfully create!";
            return response($res);
        }else{
            $res['message'] = "Create failed!";
            return response($res);
        }
    }

    public function update(Request $request, $id)
    {
        $input_data = $request->all();

        $validation = Validator::make($input_data, [
            'name' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $name = $request->input('name');

        $data = Author::where('id',$id)->first();
        $data->name = $name;

        if($data->save()){
            $res['message'] = "Successfully!";
            return response($res);
        }else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }

    public function delete(Request $request, $id)
    {
        $data = Author::where('id',$id)->first();

        if($data->delete()){
            $res['message'] = "Successfully!";
            return response($res);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }
}
