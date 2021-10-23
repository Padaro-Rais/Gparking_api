<?php

namespace App\Http\Controllers\Auth;

use App\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use App\Entreprise;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'loginMobile', 'loginPhoneMobile', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $args = array();
        $args['error'] = false;
        $user = User::where(['email' => $request->email])->first();
        $user = Hash::check($request->password, $user->password) ? $user : null;
        $user = $user->status == true ? $user : null;

        if (!$user || ! $token = Auth::login($user)) {
            $args['error'] = true;
            $args['message'] = "Unauthorized";
            return response()->json($args, 401);
        }
        return $this->respondWithUserWithToken($token, new UserResource($user));
    }

    public function loginMobile(Request $request)
    {
        $args = array();
        $args['error'] = false;
        $user = User::where(['email' => $request->email])->first();
        $user = Hash::check($request->password, $user->password) ? $user : null;

        if (!$user) {
            $args['error'] = true;
            $args['message'] = "Unauthorized";
            return response()->json($args, 401);
        }
        return response()->json(new UserResource($user), 200);
    }

    public function loginPhoneMobile(Request $request)
    {
        $args = array();
        $args['error'] = false;
        $user = User::where(['phone' => $request->phone])->first();

        if (!$user) {
            $args['error'] = true;
            $args['message'] = "Unauthorized Phone";
            return response()->json($args, 401);
        }
        return response()->json(new UserResource($user), 200);
    }

    public function register(Request $request)
    {
        $args = array();
        $args['error'] = false;

        $v = Validator::make($request->all(), [
            'salle_name' => 'required',
            'type' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'birth' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if($v->fails()) {
            $args['error'] = true;
            $args['validator'] = $v->errors();
            return response()->json($v->errors(), 400);
        }

        $data = new User();
            $data->code = $request->code ? $request->code : User::getCode();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->birth = $request->birth;
            $data->password = Hash::make($request->password);
            $data->type = $request->type;

            if($request->fname) $data->fname = $request->fname;
            if($request->phone) $data->phone = $request->phone;
            if($request->sex) $data->sex = $request->sex;
            if($request->freelance) $data->freelance = $request->freelance;
            if($request->status) $data->status = $request->status;

            DB::beginTransaction();
            $data->save();
                $salle = new Salle();
                $salle->code = $request->code ? $request->code : Salle::getCode();
                $salle->name = $request->salle_name;
                $salle->manager_id = $data->id;

                if($request->desc) $salle->desc = $request->desc;
                if($request->salle_phones) $salle->phones = $request->salle_phones;
                if($request->salle_email) $salle->email = $request->salle_email;
                if($request->adresse) $salle->adresse = $request->adresse;
                if($request->lng) $salle->lng = $request->lng;
                if($request->lat) $salle->lat = $request->lat;
                if($request->status) $salle->status = $request->status;

                $salle->save();

                if($request->salle_photo)
                {
                    $name = File::image($request->salle_photo, "images/salles");
                    if($name)
                    {
                        $image = new SalleImage();
                        $image->url = $name;
                        $image->default = 1;
                        $image->salle_id = $salle->id;
                        $image->save();
                    }
                }
            DB::commit();

        return response()->json(new UserResource($data), 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $args = array();
        $args['error'] = false;
        //dd(Auth::user()->with('role_users.role', 'role_users.permissions')->get());
        //$user = User::where(['id' => Auth::user()->id])->with('role_users.role', 'role_users.permissions')->get();
        try {
            return response()->json(new UserResource(Auth::user()), 200);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $args['error'] = true;
            $args['message'] = "Unauthorized";
            return response()->json($args, 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $args = array();
        $args['error'] = false;
        Auth::logout();
        $args['message'] = "Successfully logged out";
        return response()->json($args);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $args = array();
        $args['error'] = false;
        try {
            return $this->respondWithToken(Auth::refresh());
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $args['error'] = true;
            $args['message'] = "Unauthorized";
            return response()->json($args, 401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 190
        ]);
    }

    protected function respondWithUserWithToken($token, $user)
    {
        $entreprise = Entreprise::where(['id' => $user->id])->get();
        return response()->json([
            'user' => $user,
            'entreprise' => $entreprise && count($entreprise) > 0 ? $entreprise[0] : null,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 190
        ]);
    }
}
