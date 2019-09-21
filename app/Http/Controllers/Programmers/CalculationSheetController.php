<?php

namespace App\Http\Controllers\Programmers;

use App\Http\Requests\Programmers\CalculationSheetsUpdateRequest;
use App\Models\CalculationSheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculationSheetController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return CalculationSheet::orderBy('date')->with('rows')->get();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @param CalculationSheetsUpdateRequest $request
     * @return void
     */
    public function create(CalculationSheetsUpdateRequest $request) {
        $sheet = $request->commit();
        
        return $sheet;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }
    
    /**
     * Display the specified resource.
     *
     * @param \App\Models\CalculationSheet $calculationSheet
     * @return \Illuminate\Http\Response
     */
    public function show(CalculationSheet $calculationSheet) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CalculationSheet $calculationSheet
     * @return \Illuminate\Http\Response
     */
    public function edit(CalculationSheet $calculationSheet) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CalculationSheet $calculationSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalculationSheet $calculationSheet) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CalculationSheet $calculationSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(CalculationSheet $calculationSheet) {
        //
    }
}
