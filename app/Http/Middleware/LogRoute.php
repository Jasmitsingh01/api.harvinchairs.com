<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLogs;
use Carbon\Carbon;
use DB;

class LogRoute
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
        $response = $next($request);
        $log = [
            'api_url' => $request->getUri(),
            'method' => $request->getMethod(),
            'api_request' => json_encode($request->all()),
            'api_response' => $response->getContent(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            //DB::enableQueryLog();
            DB::table('api_logs')->insert($log);
            //dd(DB::getQueryLog());


        return $response;
    }
}
