<?php

namespace App\Http\Controllers;

use App\Models\Kiosk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KioskController extends Controller
{
    public function index()
    {
        $kiosk = Kiosk::whereNull('deleted_at')->get();

        return customResponse()
            ->data($kiosk)
            ->message('Kiosks successfully found.')
            ->success()
            ->generate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return customResponse()
                ->data(null)
                ->message($validator->errors()->all()[0])
                ->failed()
                ->generate();
        }

        $kiosk = Kiosk::create([
            'terminal_id' => $this->generateTerminalId(),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'passcode' => $this->generatePasscode(),
        ]);
        return customResponse()
            ->data(Kiosk::find($kiosk->kiosk_id))
            ->message('Kiosk has been created.')
            ->success()
            ->generate();
    }

    public function show($id)
    {
        $kiosk = Kiosk::where('kiosk_id', $id)
            ->whereNull('deleted_at')
            ->get()
            ->first();

        return customResponse()
            ->data($kiosk)
            ->message('Kiosk successfully found.')
            ->success()
            ->generate();
    }

    public function update(Request $request, $id)
    {
        $kiosk = Kiosk::updateOrCreate(
            ['kiosk_id' => (int) $id],
            [
                'location' => $request->input('location'),
                'description' => $request->input('description'),
            ]
        );

        return customResponse()
            ->data(Kiosk::find($kiosk->kiosk_id))
            ->message('Kiosk has been updated.')
            ->success()
            ->generate();
    }

    public function destroy($id)
    {
        $kiosk = Kiosk::find($id);
        if ($kiosk) {
            $kiosk->delete();
            return customResponse()
                ->data($kiosk)
                ->message('Kiosk successfully deleted.')
                ->success()
                ->generate();
        }

        return customResponse()
            ->data(null)
            ->message('Kiosk not found.')
            ->notFound()
            ->generate();
    }

    public function generateTerminalId()
    {
        $count = Kiosk::get()->count() + 1;

        return 'TERMINAL-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function generatePasscode()
    {
        return Str::random(5);
    }

    public function loginViaPasscode(Request $request) 
    {
        // request validation
        $validator = Validator::make($request->all(), 
            [
                'passcode' => 'required|
                    exists:tbl_kiosks,passcode',
            ],
            [
                'passcode.required' => 'Please enter kiosk passcode.'
            ]
        );

        if ($validator->fails()){
            return customResponse()
                ->message('Please enter kiosk passcode.')
                ->failed(400)
                ->errors($validator->errors())
                ->generate();
        }
          
        $kiosk = Kiosk::where('passcode', $request->input('passcode'))
            ->whereNull('deleted_at')
            ->get()
            ->first();

        return customResponse()
            ->data($kiosk)
            ->message('Kiosk successfully found.')
            ->success()
            ->generate();
    }
}
