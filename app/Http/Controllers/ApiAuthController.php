<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
	public function login(Request $request){
		$fields = $request->validate([
			'email' => 'required|string',
			'password' => 'required|string'
		]);

		$user = User::where('email', $fields['email'])->first();
		if(!$user || !Hash::check($fields['password'],$user->password)){
			return response(
				[
					'message' => 'Wrong Credentials'
				], 400);
		}

		$token = $user->createToken('myapptoken')->plainTextToken;

		$response = [
			'user' => $user,
			'token' => $token
		];

		return response($response, 200);
	}

	public function logout(Request $request){
		auth()->user()->tokens()->delete();

		return [
			'message' => 'Logged out'
		];
	}

	public function ping(Request $request){
		$user = auth()->user();
		$response = [
			'user' => $user
		];
		return response($response, 200);
	}
}