<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function form(Request $request)
    {
        if ($request->filled('certificate_number')) {
            $certificate = Certificate::where('certificate_number', trim($request->certificate_number))->first();

            return view('verify.index', [
                'result' => $certificate,
                'searched' => true,
            ]);
        }

        return view('verify.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'certificate_number' => ['required', 'string'],
        ]);

        $certificate = Certificate::where('certificate_number', trim($request->certificate_number))->first();

        return view('verify.index', [
            'result' => $certificate,
            'searched' => true,
        ]);
    }
}