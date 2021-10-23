<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormuleResource;
use App\Http\Resources\SalleResource;
use App\Http\Resources\SouscriptionResource;
use App\Http\Resources\UserResource;
use App\User;
use App\Entreprise;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::orderBy('created_at', 'DESC')->get());
    }


    public function store(Request $request)
    {
        $args = array();
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'name' => 'required',
            'matricule' => 'required',
            'adresse' => 'required',
            'telephone' => 'required',

            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'photo' => 'nullable',

        ]);



        if($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $entreprise= new Entreprise();
            $entreprise->name = $request->name;
            $entreprise->matricule = $request->matricule;
            $entreprise->adresse = $request->adresse;
            $entreprise->telephone = $request->telephone;

            if($request->name) $entreprise->name = $request->name;
            if($request->matricule) $entreprise->matricule = $request->matricule;
            if($request->adresse) $entreprise->adresse = $request->adresse;
            if($request->telephone) $entreprise->telephone = $request->telephone;

            if($request->photo_url)
            {
                $name = File::image($request->photo, "Images/profiles");
                if($name)
                {
                    $data->photo_url = $name;
                }
            }
            $entreprise->save();


            $user= new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->entriprise_id = $entreprise->id;

            if($request->username) $user->username = $request->username;
            if($request->email) $user->email = $request->email;
            if($request->password) $user->password = $request->password;
            if($request->entriprise_id) $user->entriprise_id = $entreprise->id;
            $user->save();

        return response()->json(new UserResource($user), 201);
    }


    public function show($id)
    {
        $args = array();
        $args['error'] = false;

        $data = User::find($id);
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
          return response()->json($args, 404);
        }

        return response()->json(new UserResource($data), 200);
    }


    public function update(Request $request, $id)
    {
        $args = array();
        $args['error'] = false;

        $data = User::find($id);
        $all = $request->all();
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else{

            unset($all['password']);
            unset($all['password_old']);
            unset($all['password_confirmation']);

            if($request->password && $request->password_old  && $request->password_confirmation)
            {
                if(Hash::check($request->password_old, $data->password))
                {
                    if($request->password != $request->password_confirmation){
                        $args['error'] = true;
                        $args['message'] = 'Bad confirmated password';
                        return response()->json($args, 400);
                    }
                    $all['password'] = Hash::make($request->password);
                }else {
                    $args['error'] = true;
                    $args['message'] = 'Bad old password';
                    return response()->json($args, 400);
                }
            }

            if(isset($all['photo']))
            {
                $name = File::image($all['photo']);
                if($name)
                {
                    $all['photo'] = $name;
                }else $all['photo'] = $data->photo;
                File::deleteImage($all['photo'], "profiles");
            }

            $data->update($all);

            return response()->json(new UserResource($data), 200);
        }
    }

    public function active($id)
    {
        $data = User::find($id);
        if($data->status)
        {
            $data->update([
                'status' => 0,
            ]);
        }else{
            $data->update([
                'status' => 1,
            ]);
        }
        return response()->json(new UserResource($data), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $args = array();
        $args['error'] = false;

        $data = User::find($id);
        if(is_null($data))
        {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }
        else
        {
            $data->delete();
            // File::deleteImage($data->photo, "profiles");

            Entreprise::where(['id' => $data->id])->delete();
            $data->delete();
            return response()->json(new UserResource($data), 200);
        }
    }
}
