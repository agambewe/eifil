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

    public function trash()
    {
        return Author::onlyTrashed()->get();
    }

    public function trashFind($id){
        $author = Author::onlyTrashed()->where('id',$id);

        if (is_null($author)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($author->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $author;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function trashRestore(){
        $author = Author::onlyTrashed();

        if (is_null($author)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            if($author->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $author;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function trashEmpty(){
        $author = Author::onlyTrashed();

        if (is_null($author)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            if($author->forceDelete()){
                $res['message'] = "Data has been successfully deleted!";
                $res['data'] = $author;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }
    
    public function find($id){
        $data = Author::find($id);

        if (is_null($data)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $data;
            return response($res);
        }
    }

    public function store(Request $request)
    {
        $input_data = $request->all();

        $validation = Validator::make($input_data, [
            'name' => 'required|unique:authors'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }
        
        $author = new Author();     
        $author->name = $input_data['name'];

        if($author->save()){
            $res['message'] = "Data has been successfully created!";
            $res['data'] = $author;
            return response($res);
        }else{
            $res['errors'] = "Failed!";
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
            $res['message'] = "Data has been successfully updated!";
            return response($res);
        }else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function delete(Request $request, $id)
    {
        $data = Author::where('id',$id)->first();

        if($data->delete()){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function multipleDelete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $checked = $request->input('id');

        if(Author::destroy($checked)){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }
}
