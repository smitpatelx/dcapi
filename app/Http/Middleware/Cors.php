<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
   
    private static $allowedOriginsWhitelist = [];
  
      // All the headers must be a string
  
      private static $allowedOrigin = '';
  
      private static $allowedMethods = '';
  
      private static $allowCredentials = '';
  
      private static $allowedHeaders = '';
  
      public function __construct(){
        //   var_dump(explode(',', config('services.cors.allowedOrigin')));
        static::$allowedOriginsWhitelist = explode(',', config('services.cors.allowedOrigin'));
        static::$allowedMethods = config('services.cors.allowedMethods');
        static::$allowCredentials = config('services.cors.allowCredentials');
        static::$allowedHeaders = config('services.cors.allowedHeaders');
      }
      /**
       * Handle an incoming request.
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \Closure  $next
       * @return mixed
       */
      public function handle($request, Closure $next)
      {
        if (! $this->isCorsRequest($request))
        {
          return $next($request);
        }
  
        static::$allowedOrigin = $this->resolveAllowedOrigin($request);
  
        static::$allowedHeaders = $this->resolveAllowedHeaders($request);
  
        $headers = [
          'Access-Control-Allow-Origin'       => static::$allowedOrigin,
          'Access-Control-Allow-Methods'      => static::$allowedMethods,
          'Access-Control-Allow-Headers'      => static::$allowedHeaders,
          'Access-Control-Allow-Credentials'  => static::$allowCredentials,
        ];
  
        // For preflighted requests
        if ($request->getMethod() === 'OPTIONS')
        {
          return response('', 200)->withHeaders($headers);
        }
  
        $response = $next($request)->withHeaders($headers);
  
        return $response;
      }
  
      /**
       * Incoming request is a CORS request if the Origin
       * header is set and Origin !== Host
       *
       * @param  \Illuminate\Http\Request  $request
       */
      private function isCorsRequest($request)
      {
        $requestHasOrigin = $request->headers->has('Origin');
  
        if ($requestHasOrigin)
        {
          $origin = $request->headers->get('Origin');
  
          $host = $request->getSchemeAndHttpHost();
  
          if ($origin !== $host)
          {
            return true;
          }
        }
  
        return false;
      }
  
      /**
       * Dynamic resolution of allowed origin since we can't
       * pass multiple domains to the header. The appropriate
       * domain is set in the Access-Control-Allow-Origin header
       * only if it is present in the whitelist.
       *
       * @param  \Illuminate\Http\Request  $request
       */
      private function resolveAllowedOrigin($request)
      {
        $allowedOrigin = static::$allowedOrigin;
  
        // If origin is in our $allowedOriginsWhitelist
        // then we send that in Access-Control-Allow-Origin
  
        $origin = $request->headers->get('Origin');
  
        if (in_array($origin, static::$allowedOriginsWhitelist))
        {
          $allowedOrigin = $origin;
        }
  
        return $allowedOrigin;
      }
  
      /**
       * Take the incoming client request headers
       * and return. Will be used to pass in Access-Control-Allow-Headers
       *
       * @param  \Illuminate\Http\Request  $request
       */
      private function resolveAllowedHeaders($request)
      {
        $allowedHeaders = $request->headers->get('Access-Control-Request-Headers');
  
        return $allowedHeaders;
      }
}
