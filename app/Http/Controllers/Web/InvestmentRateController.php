<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRate;
use Illuminate\Http\Request;

class InvestmentRateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(InvestmentRate::latest()->get());
        }

        return view('tasas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'instrument_name' => 'required|string|max:255',
            'annual_rate'     => 'required|numeric|min:0|max:999.99',
        ]);

        $rate = InvestmentRate::create($request->only('instrument_name', 'annual_rate'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tasa creada correctamente.', 'rate' => $rate]);
        }
        return back()->with('success', 'Tasa creada correctamente.');
    }

    public function update(Request $request, InvestmentRate $rate)
    {
        $request->validate([
            'instrument_name' => 'required|string|max:255',
            'annual_rate'     => 'required|numeric|min:0|max:999.99',
        ]);

        $rate->update($request->only('instrument_name', 'annual_rate'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tasa actualizada correctamente.', 'rate' => $rate]);
        }
        return back()->with('success', 'Tasa actualizada correctamente.');
    }

    public function destroy(InvestmentRate $rate)
    {
        $rate->delete();
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Tasa eliminada correctamente.']);
        }
        return back()->with('success', 'Tasa eliminada correctamente.');
    }
}
