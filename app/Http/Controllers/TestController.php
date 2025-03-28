<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test",
     *     summary="Test swagger route",
     *     tags={"Test"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function test() {

    }
}
