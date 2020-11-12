<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Response;
use Validator;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['show', 'find']]);
    }
    
    public function show()
    {
        return Category::orderBy('name', 'ASC')->get();
    }

    public function trash()
    {
        return Category::onlyTrashed()->get();
    }

    public function trashFind($id){
        $category = Category::onlyTrashed()->where('id',$id);

        if (is_null($category)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($category->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $category;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function trashRestore(){
        $category = Category::onlyTrashed();

        if (is_null($category)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($category->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $category;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }
    
    public function trashEmpty(){
        $category = Category::onlyTrashed();

        if (is_null($category)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            if($category->forceDelete()){
                $res['message'] = "Data has been successfully deleted!";
                $res['data'] = $category;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function find($id){
        $data = Category::find($id);

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
            'name' => 'required|unique:categories'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 400);
        }

        $category = new Category();     
        $category->name = $input_data['name'];

        if($category->save()){
            $res['message'] = "Data has been successfully created!";
            return response($res);
        }else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function update(Request $request, $id)
    {
        $input_data = $request->all();

        $data = Category::where('id',$id)->first();

        $validation = Validator::make($input_data, [
            'name' => ['required', Rule::unique('categories')->ignore($data)],
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $name = $request->input('name');
        $data = Category::where('id',$id)->first();
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
        
        $category = Category::has('articles')->find($id);
        if($category){
            return response()->json(['errors' => "Category has been used on article!"], 422);
        }

        $data = Category::where('id',$id)->first();
        if($data->delete()){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function trashDelete(Request $request, $id)
    {
        $data = Category::onlyTrashed()->where('id',$id)->first();

        if($data->forceDelete()){
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

        foreach ($checked as $relationCheck) {
            $relations = Category::has('articles')->find($relationCheck);
            if($relations){
                return response()->json(['errors' => "Category has been used on article!"], 422);
            }
        }

        if(Category::destroy($checked)){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function multipleDeleteTrash(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        $checked = $request->input('id');

        $forceDelete = Category::onlyTrashed()
                ->whereIn('id', $checked)
                ->forceDelete();

        if($forceDelete){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }
}
