<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Article;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Response;
use Validator;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['show', 'find', 'findImage', 'findByCategory', 'findByAuthor', 'findByHashtag', 'topByHashtag']]);
        // $this->middleware('auth:api');
    }

    public function show()
    {
        $datas = Article::with(['detailAuthor', 'detailCategory'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
        foreach($datas as $data){
            preg_match( '@src="([^"]+)"@' , $data->description, $match );
            $src = array_pop($match);
            $data->image = $src;
        }

        if (is_null($datas)){
            return response("Failed!");
        }else{
            return response($datas);
        }
    }

    public function trash()
    {
        return Article::onlyTrashed()->with(['detailAuthor', 'detailCategory'])->get();
    }

    public function trashFind($id){
        $article = Article::onlyTrashed()->where('id',$id);

        if (is_null($article)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($article->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $article;
                return response($res);
            }else{
                $res['errors'] = "Failed";
                return response($res);
            }
        }
    }

    public function trashRestore(){
        $article = Article::onlyTrashed();

        if (is_null($article)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($article->restore()){
                $res['message'] = "Data has been successfully restored!";
                $res['data'] = $article;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function trashEmpty(){
        $article = Article::onlyTrashed();

        if (is_null($article)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if($article->forceDelete()){
                $res['message'] = "Data has been successfully Deleted!";
                $res['data'] = $article;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }
    
    public function find($id){
        $data = Article::with(['detailAuthor', 'detailCategory'])
                    ->find($id);

        if (is_null($data)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $data->view_count++;
            if($data->save()){
                $res['message'] = "Data has been successfully fetched!";
                $res['data'] = $data;
                return response($res);
            }else{
                $res['errors'] = "Failed!";
                return response($res);
            }
        }
    }

    public function findByCategory($id){
        $datas = Article::with(['detailAuthor', 'detailCategory'])
                    ->whereIn('id_category',array($id))->get();

        foreach($datas as $data){
            preg_match( '@src="([^"]+)"@' , $data->description, $match );
            $src = array_pop($match);
            $data->image = $src;
        }

        if (is_null($datas)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $datas;
            return response($res);
        }
    }

    public function findByAuthor($id){
        $datas = Article::with(['detailAuthor', 'detailCategory'])
                    ->whereIn('id_author',array($id))
                    ->orderBy('description', 'ASC')
                    ->get();

        foreach($datas as $data){
            preg_match( '@src="([^"]+)"@' , $data->description, $match );
            $src = array_pop($match);
            $data->image = $src;
        }

        if (is_null($datas)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $datas;
            return response($res);
        }
    }

    public function findByHashtag($hashtag){
        $datas = Article::with(['detailAuthor', 'detailCategory'])
                    ->orWhere('hashtag', 'like', '%' . $hashtag . '%')
                    ->orderBy('view_count', 'DESC')
                    ->get();

        foreach($datas as $data){
            preg_match( '@src="([^"]+)"@' , $data->description, $match );
            $src = array_pop($match);
            $data->image = $src;
        }

        if (is_null($datas)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $datas;
            return response($res);
        }
    }

    public function topByHashtag($id){
        $data = Article::find($id)->hashtag;
        $data = str_replace("#","",$data);
        $pieces = explode(",", $data);
        
        $top = Article::where(function($query) use ($pieces){
            foreach($pieces as $piece){
                $query = $query->orWhere('hashtag', 'LIKE', "%$piece%");
            }
            return $query;
        });
        $results = $top->with(['detailAuthor', 'detailCategory'])
                    ->where('id', '<>', $id)
                    ->orderBy('view_count', 'DESC')
                    ->take(4)
                    ->get();

        foreach($results as $result){
            preg_match( '@src="([^"]+)"@' , $result->description, $match );
            $src = array_pop($match);
            $result->image = $src;
        }

        if (is_null($results)){
            $res['errors'] = "Failed!";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $results;
            return response($res);
        }
    }

    public function findImage($id){
        $data = Article::find($id)->description;
        preg_match( '@src="([^"]+)"@' , $data, $match );
        $src = array_pop($match);

        if(!$src){
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = "null";
            return response($res);
        }else{
            $res['message'] = "Data has been successfully fetched!";
            $res['data'] = $src;
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
            'hashtag' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $article = new Article();     
        $article->title = $input_data['title'];
        $article->description = $input_data['description'];
        $article->id_author = $input_data['id_author'];
        $article->id_category = $input_data['id_category'];
        $article->hashtag = $input_data['hashtag'];

        if($article->save()){
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

        $validation = Validator::make($input_data, [
            'title' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
            'hashtag' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $title = $input_data['title'];
        $description = $input_data['description'];
        $id_author = $input_data['id_author'];
        $id_category = $input_data['id_category'];
        $hashtag = $input_data['hashtag'];

        $data = Article::where('id',$id)->first();
        $data->title = $title;
        $data->description = $description;
        $data->id_author = $id_author;
        $data->id_category = $id_category;
        $data->hashtag = $hashtag;

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
        $data = Article::where('id',$id)->first();
        
        if($data->delete()){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function deleteAll()
    {
        $del = Article::whereNull('deleted_at')->delete();
        if($del){
            $res['message'] = "Data has been successfully deleted!";
            return response($res);
        }
        else{
            $res['errors'] = "Failed!";
            return response($res);
        }
    }

    public function deleteAllConfirm(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $id = $request->input('id');

        $data = User::where('id',$id)->first();
        if(is_null($data)){
            $res['message'] = "Failed!";
            return response($res);
        }else{
            if(!Hash::check($request->input('password'), $data->password)){
                $res['errors'] = "Wrong Password!";
                return response($res, 422);
            }else{
                $this->deleteAll();
                $res['message'] = "Data has been successfully deleted!";
                return response($res);  
            }
        }
        $res['errors'] = "Wrong id!";
        return response($res);
    }

    public function trashDelete(Request $request, $id)
    {
        $data = Article::onlyTrashed()->where('id',$id)->first();

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
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        $checked = $request->input('id');

        if(Article::destroy($checked)){
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

        $forceDelete = Article::onlyTrashed()
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

    public function storeImg()
    {
        // Allowed the origins to upload 
        // $accepted_origins = array("http://localhost", "https://techarise.com/");
        // Images upload dir path
        // $imgFolder = public_path() . "/uploads/";
        $imgFolder = "/home/eifilin1/api.eifil-indonesia.org/uploads/";
        reset($_FILES);
        $tmp = current($_FILES);
        if(is_uploaded_file($tmp['tmp_name'])){
            if(isset($_SERVER['HTTP_ORIGIN'])){
                // if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
                    // header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
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