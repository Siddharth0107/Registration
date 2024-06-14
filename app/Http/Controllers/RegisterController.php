<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json([
                'status' => "success",
                'message' => "All the Users Retrieved...!!!",
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "User creation failed!",
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function createUser(Request $request)
    {
        try {

            $rules = [
                "name" => "required|min:2",
                "email" => "required|email",
                "surname" => "required",
                "password" => "required|min:8|max:15",
                "birthdate" => "required|date"
            ];

            $messages = [
                "required" => "This field is required",
                "email" => "Email format is invalid",
                "date" => "Date format is invalid",
                "min" => "This field should be at least :min characters",
                "max" => "This field should not exceed :max characters"
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => "error",
                    'errors' => $validator->errors(),
                    'data' => null
                ], 422);
            }

            $user = User::create($request->all());
            return response()->json([
                'status' => "success",
                'message' => "User Created successfully!",
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "error",
                'messgae' => $th->getMessage(),
                "data" => null,
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $rules = [
                "email" => "required|email",
                "password" => "required",
            ];

            $messages = [
                "required" => "This field is required",
                "email" => "Email format is invalid",
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => "error",
                    'errors' => $validator->errors(),
                    'data' => null
                ], 422);
            }

            $user = $request->all();
            if (Auth::attempt($user)) {
                return response()->json([
                    "status" => "success",
                    "message" => "Successfully Loged In",
                    "Data" => $user
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
                "Data" => null
            ]);
        }
    }

    public function deleteUser($userID)
    {
        try {
            $user = User::find($userID);

            if ($user) {
                $user->delete();
            }

            return response()->json([
                "status" => "success",
                "message" => "Successfully Deleted User",
                "Data" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
                "Data" => null
            ]);
        }
    }


    public function updateUser(Request $request, $userID)
    {
        try {
            $allowedFields = ['name', 'surname', 'birthdate'];

            // Check for unexpected fields
            $unexpectedFields = array_diff(array_keys($request->all()), $allowedFields);
            if (!empty($unexpectedFields)) {
                return response()->json([
                    'status' => false,
                    'error' => [
                        'unexpected_fields' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)
                    ],
                    "data" => null
                ], 422);
            }

            $user = User::find($userID);

            if (!$user) {
                return response()->json([
                    "status" => "error",
                    "message" => "User not found",
                    "Data" => null
                ]);
            }

            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->birthdate = $request->birthdate;

            $rules = [
                "name" => "required|min:2",
                "surname" => "required",
                "birthdate" => "required|date"
            ];

            $messages = [
                "required" => "This field is required",
                "date" => "Date format is invalid",
                "min" => "This field should be at least :min characters",
                "max" => "This field should not exceed :max characters"
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => "error",
                    'errors' => $validator->errors(),
                    'data' => null
                ], 422);
            }

            $user->save();

            return response()->json([
                "status" => "success",
                "message" => "Record Updated Successfully",
                "Data" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage(),
                "Data" => null
            ]);
        }
    }
}
