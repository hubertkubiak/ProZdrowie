<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeasurementsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'pal' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);
    }

    public function index()
    {
        $measurements = \Auth::user()->measurements()->get();
        return view('measurements.index', ['measurements' => $measurements, 'user' => \Auth::user()]);
    }

    public function save(Request $request)
    {
        $this->validator($request->all())->validate();
        $measurement = new Measurement($request->all());
        \Auth::user()->measurements()->save($measurement);

        return redirect()->route('measurement.index');
    }

    public function update(Request $request, Measurement $measurement)
    {
        $this->validator($request->all())->validate();
        $measurement->update($request->all());
        \Auth::user()->measurements()->save($measurement);
        return redirect()->route('measurement.index');
    }

    public function edit(Measurement $measurement)
    {
        return view('measurements.edit', ['measurement' => $measurement]);
    }

    public function create()
    {
        return view('measurements.create');
    }

    public function delete(Measurement $measurement)
    {
        $measurement->delete();
        return redirect()->back();
    }
}
