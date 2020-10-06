<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Http\Request;
use Response;
use Validator;

class ArticleController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->middleware('auth:api');
    }

    public function show()
    {
        return Article::with(['detailAuthor', 'detailCategory'])->get();
    }

    public function find($id){
        $data = Article::find($id);

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
            'title' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
            'hastag' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $article = new Article();     
        $article->title = $input_data['title'];
        $article->description = $input_data['description'];
        $article->id_author = $input_data['id_author'];
        $article->id_category = $input_data['id_category'];
        $article->hastag = $input_data['hastag'];

        if($article->save()){
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
            'title' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
            'hastag' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $title = $input_data['title'];
        $description = $input_data['description'];
        $id_author = $input_data['id_author'];
        $id_category = $input_data['id_category'];
        $hastag = $input_data['hastag'];

        $data = Article::where('id',$id)->first();
        $data->title = $title;
        $data->description = $description;
        $data->id_author = $id_author;
        $data->id_category = $id_category;
        $data->hastag = $hastag;

        if($data->save()){
            $res['message'] = "Data article berhasil diubah!";
            return response($res);
        }else{
            $res['message'] = "Data article gagal diubah!";
            return response($res);
        }
    }

    public function delete(Request $request, $id)
    {
        $data = Article::where('id',$id)->first();

        if($data->delete()){
            $res['message'] = "Data article berhasil dihapus!";
            return response($res);
        }
        else{
            $res['message'] = "Data article gagal dihapus!";
            return response($res);
        }
    }

    public function storeImg()
    {
        // Allowed the origins to upload 
        // $accepted_origins = array("http://localhost", "https://techarise.com/");
        // Images upload dir path
        $imgFolder = public_path() . "/uploads/";
        reset($_FILES);
        $tmp = current($_FILES);
        if(is_uploaded_file($tmp['tmp_name'])){
            if(isset($_SERVER['HTTP_ORIGIN'])){
                // if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                // }else{
                //     header("HTTP/1.1 403 Origin Denied");
                //     return;
                // }
            }
            // check valid file name
            if(preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $tmp['name'])){
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }
            // check and Verify extension
            if(!in_array(strtolower(pathinfo($tmp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))){
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }
        
            // Accept upload if there was no origin, or if it is an accepted origin
            $filePath = $imgFolder . $tmp['name'];
            move_uploaded_file($tmp['tmp_name'], $filePath);
            // return successful JSON.
            // echo json_encode(array('location' => $filePath));
            echo json_encode(array('location' => 'http://api.eifil-indonesia.org/uploads/' . $tmp['name']));
        } else {
            header("HTTP/1.1 500 Server Error");
	}
    }
}
?>