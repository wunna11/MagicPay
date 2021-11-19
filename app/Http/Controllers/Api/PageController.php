<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        $data = new ProfileResource($user);
        $res = new Response('success', $data);
        return $res->success();
    }
}
