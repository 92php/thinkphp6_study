<?php

declare (strict_types=1);

namespace app\middleware;

use think\Response;


class Test
{
    public function handle($request, \Closure $next)
    {
        if ($request->param('name') == 'think') {
            return redirect('/think');
        }

        return $next($request);
    }

}