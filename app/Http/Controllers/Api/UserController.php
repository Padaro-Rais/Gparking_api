<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormuleResource;
use App\Http\Resources\SalleResource;
use App\Http\Resources\SouscriptionResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\EntrepriseResource;
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
        return UserResource::collection(
            User::where('status',1)->orderBy('created_at', 'DESC')->get()
        );
    }

    public function store(Request $request)
    {
        $args = [];
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

        if ($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $entreprise = new Entreprise();
        $entreprise->name = $request->name;
        $entreprise->matricule = $request->matricule;
        $entreprise->adresse = $request->adresse;
        $entreprise->telephone = $request->telephone;

    
        if ($request->photo) {
            $name = File::image($request->photo, 'Images/profiles');
            if ($name) {
                $entreprise->photo = $name;
            }
        }
        $entreprise->save();

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->entriprise_id = $entreprise->id;
        $user->save();

        return response()->json(new UserResource($user), 201);
    }

    public function show($id)
    {
        $args = [];
        $args['error'] = false;

        $data = User::find($id);
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        }

        return response()->json(new UserResource($data), 200);
    }

    public function update(Request $request, $id)
    {
        $args = [];
        $args['error'] = false;

        $data = Entreprise::find($id);
        $all = $request->all();
        if (is_null($data)) {
            $args['error'] = true;
            $args['message'] = 'Resource not found';
            return response()->json($args, 404);
        } else {
            $data->update($all);
            User::where('entriprise_id', $id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' =>Hash::make($request->password)
            ]);
            return response()->json(new EntrepriseResource($data), 200);
        }
    }

    public function active($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Entreprise::find($id);

        Entreprise::where('id', $id)->update(['status' => 0]);

        User::where('entriprise_id', $id)->update(['status' => 0]);

        return response()->json(['err' => false, 'success' => true, 'message' => 'entrueprise archivée']);
    }
}
