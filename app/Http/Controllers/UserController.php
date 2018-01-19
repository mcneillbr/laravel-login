<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use \Exception;
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
        $users = User::select('id', 'name', 'email', DB::raw("'' as `user_pass`"), 'state');
        return response()->json($users->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = ['saved' => false, 'message' => ''];
        $statusCode = 501;
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $statusCode = 422;
            throw new Exception(json_encode($validator->errors()), 2000);
        }
        try {
            $u = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'state' => filter_var($request->input('state'), FILTER_VALIDATE_BOOLEAN),
          ]);
            $statusCode = 200;
            $result['saved'] = true;
            $result['message'] = 'OK';
        } catch (Exception $e) {
          $result['message'] = $e->getMessage();
        } finally {
            return response()->json($result, $statusCode);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = ['saved' => false, 'message' => ''];
        $statusCode = 501;
        $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            $statusCode = 422;
            throw new Exception(json_encode($validator->errors()), 2000);
        }
        try {
            $u = User::find($id);
            if (is_null($u)) {
                $statusCode = 422;
                throw new Exception('o usuário não existe', 2000);
            }

            $u->name = $request->input('name');
            $u->state = filter_var($request->input('state'), FILTER_VALIDATE_BOOLEAN);
            $this->updateEmail($u, $request->all());
            $this->updatePassword($u, $request->all());
            $result['saved'] = $u->saveOrFail();

            $statusCode = 200;
            $result['message'] = 'OK';
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        } finally {
            return response()->json($result, $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = ['saved' => false, 'message' => ''];
        try {
          $u = User::find($id);
          if (is_null($u)) {
              $statusCode = 422;
              throw new Exception('o usuário não existe', 2000);
          }

          $statusCode = 200;
          $result['message'] = $u->delete();
          
        }  catch (Exception $e) {
            $result['message'] = $e->getMessage();
        } finally {
            return response()->json($result, $statusCode);
        }

        return response()->json([]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
        ]);
    }

    private function updateEmail($user, $data){
      if($data['email'] === $user->email){
        return FALSE;
      }
      $v = Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
      ]);
      if ($v->fails()) {
        throw new Exception(json_encode($v->errors()), 2000);
      }
      $user->email = $data['email'];

      return TRUE;

    }
    private function updatePassword($user, $data){
      $v = Validator::make($data, [
                  'password' => 'required|string|min:6|confirmed',
      ]);
      if ($v->fails()) {
        return FALSE;
      }
      $user->password = bcrypt($data['password']);
     return TRUE;
    }
}
