<?php

namespace App\Http\Controllers;

use App\User;
use App\UserFile;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {

        $users = User::all();
        $json = array();


        if (!empty($users)) {

            $json = array(

                "status" => 200,
                "files" => $users

            );

            return json_encode($json, true);
        } else {

            $json = array(

                "status" => 200,
                "total_registros" => 0,
                "detalles" => "No hay ningún usuario en el archivo"

            );
        }

        return json_encode($json, true);
    }

    /*=============================================
    Mostrar 
    =============================================*/

    public function show()
    {

        $data = User::with(['files' => function ($query) {
            $query->select("id", "user_id", "file_name", "url", "created_at");
        }])->get('id');

        if (!empty($data)) {

            $json = array(
                "status" => 200,
                "files" => $data
            );
        } else {

            $json = array(
                "status" => 200,
                "detalles" => "No hay ningún archivo registrado"

            );
        }

        return json_encode($json, true);
    }
}
