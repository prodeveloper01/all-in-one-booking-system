<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

	public function sendResetLinkEmail(Request $request)
	{
		try {
			$this->validateEmail( $request );
		}catch (\Exception $e){
			return response()->json([
				'status' => false,
				'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => $e->getMessage()])->render()
			]);
		}

		$response = $this->broker()->sendResetLink(
			$this->credentials($request)
		);

		$data = Password::RESET_LINK_SENT
			? $this->sendResetLinkResponse($request, $response)
			: $this->sendResetLinkFailedResponse($request, $response);

		return response()->json([
			'status' => true,
			'message' => view('Frontend::components.alert', ['type' => 'success', 'message' => $data->getData()->message])->render()
		]);
	}
}
