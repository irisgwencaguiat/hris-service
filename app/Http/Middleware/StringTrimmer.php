<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StringTrimmer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        
        if ($input) {
            array_walk_recursive($input, function (&$item) {
                $item = trim(preg_replace('/\s\s+/', ' ', str_replace('\n', ' ', $item)));
                $item = ($item == "") ? null : $item;
            });

           $request->merge($input);
        }

        return $next($request);
    }
}
