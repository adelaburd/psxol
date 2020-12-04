<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{

    /*=============================================
    Crear un registro
    =============================================*/

    public function store(Request $request)
    {

        //Recoger datos
        $datos = array(
            "user_id" => $request->input("user_id"),
            "file_name" => $request->file("file_name"),
          
        );
       
        if (!empty($datos)) {
            //validacion del usuario en la tabla usuarios
            $usuario = DB::table('users')->where('id', $datos['user_id'])->exists();
            if ($usuario == null) {
                $json = array(
                    "status" => 404,
                    "detalle" => 'Usuario  no encontrado'
                );
                return json_encode($json, true);
            }
            
            //Validar datos
            $validator = Validator::make($datos, [
                'user_id' => 'required|numeric',
                'file_name' => 'required|mimes:jpg|max:2048',
            ]);

            //Si falla la validación
            if ($validator->fails()) {
                $errors = $validator->errors();
                $json = array(
                    "status" => 400,
                    "detalle" => $errors
                );
                return json_encode($json, true);
            } else {
                //store file into 
                $url = $datos["file_name"]->store('/storage/app/public/fotos');

                if($url == null){ 
                    $json = array(
                        "status" => 201,
                        "detalle" => "No se pudo subir el archivo"
                    );
                    return json_encode($json, true);
                }
              

                $file = new UserFile();
                $file->user_id = $datos["user_id"];
                $file->file_name = $datos["file_name"];
                $file->url = $url;
                $file->save();
                $user_id = $datos["user_id"];
                $last = UserFile::select('id', 'file_name', 'url', 'created_at')->latest()->first();
                $files = UserFile::select('id', 'file_name', 'url', 'created_at')->where('user_id', $user_id)->orderBy('created_at', 'asc')->orderBy('file_name', 'asc')->get();

                $json = array(
                    "status" => 200,
                    "user_id" => $user_id,
                    "uploaded_file" => $last,
                    "files" => $files
                );

                return json_encode($json, true);
            }
        } else {

            $json = array(
                "status" => 404,
                "detalle" => "Los datos no pueden estar vacíos"

            );
        }
    }


    /*=============================================
    Mostrar 
    =============================================*/

    public function show($id)
    {

        $files =  DB::table('user_files')->select('id', 'file_name', 'url', 'created_at')->where('user_id', $id)->get();

        if (!empty($files)) {

            $json = array(

                "status" => 200,
                "user_id" => $id,
                "files" => $files
            );
        } else {

            $json = array(
                "status" => 200,
                "detalles" => "No hay ningún archivo registrado"

            );
        }

        return json_encode($json, true);
    }




}//end class
