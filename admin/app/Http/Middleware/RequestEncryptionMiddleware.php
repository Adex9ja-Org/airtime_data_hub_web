<?php

namespace App\Http\Middleware;

use App\Model\Crypto;
use Closure;

class RequestEncryptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('e_data')){
            $decryptedText = $request->input('e_data');
            $key = config('app.app_api_key');
            $decrypted = Crypto::decrypt($decryptedText, $key);
            $json = json_decode($decrypted, true);
            $request->replace($json);

            $response = $next($request);

            $encryptedText = Crypto::encrypt($response->content(), $key);
            $data = ['e_data' => $encryptedText];
            $response->setContent(\GuzzleHttp\json_encode($data));
            return $response;
        }
        else
            return $next($request);
    }
}
