<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function avatar(): Response
    {
        $user = auth()->user();

        if (!$user->avatar || $user->avatar === 'icon-user') {
            return response(
                file_get_contents('https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=EBF4FF&color=7F9CF5&size=200'),
                200
            )->header('Content-Type', 'image/png');
        }

        $binary = base64_decode($user->avatar);
        $mime   = (new \finfo(FILEINFO_MIME_TYPE))->buffer($binary);

        return response($binary, 200)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'private, max-age=86400');
    }
}
