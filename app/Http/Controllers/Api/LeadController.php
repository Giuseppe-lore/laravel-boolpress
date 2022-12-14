<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lead;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactThanks;

class LeadController extends Controller
{
    public function store(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:60000',
        ]);

        if($validator->fails()) { 
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Salvo i dati nel database
        $new_lead = new Lead();
        $new_lead->fill($data);
        $new_lead->save();

        // Invio la mail di ringraziamento
        Mail::to($data['email'])->send(new ContactThanks());

        return response()->json([
            'success' => true
        ]);
    }
}
