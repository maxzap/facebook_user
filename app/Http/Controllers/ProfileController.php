<?php

namespace App\Http\Controllers;

use Facebook\Facebook;
use App\Profile;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function login()
    {
        $fb = new Facebook;
        $helper = $fb->getRedirectLoginHelper();
        $permissions = array(); // Optional permissions
        $loginUrl = $helper->getLoginUrl('http://localhost:8000/portal', $permissions);

        echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
    }

    public function index()
    {
        session_start();

        $fb = new Facebook;
        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state']=$_GET['state'];
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $errors[] = $e->getMessage();
            return view('perfil', compact('errors'));

        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $errors[] = $e->getMessage();
            return view('perfil', compact('errors'));
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        // echo '<h3>Access Token</h3>';
        // var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        // echo '<h3>Metadata</h3>';
        // var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
      $tokenMetadata->validateAppId('1533137420137387'); // Replace {app-id} with your app id
      // If you know the user ID this access token belongs to, you can validate it here
      //$tokenMetadata->validateUserId('123');
      $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;
        return view('perfil');
        // return redirect()->route('detalle_perfil');
        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');
    }

    public function profile(Request $request)
    {
        session_start();
        $id = $request->input('id');
        $fb = new Facebook;

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/' . $id, $_SESSION['fb_access_token']);

        } catch (FacebookResponseException $e) {

            $errors[] = $e->getMessage();
            return view('perfil', compact('errors'));

        } catch (FacebookSDKException $e) {

          $errors[] = $e->getMessage();
          return view('perfil', compact('errors'));

        }
        $user = $response->getGraphUser();
        return view('perfil', compact('user', 'errors'));
    }
}
