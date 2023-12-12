<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index (){
        $excelFile = './public/a.xlsx';


        // Load the spreadsheet
        $spreadsheet = IOFactory::load($excelFile);

        // Select the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();


        // Get the highest column and row numbers referenced in the worksheet
        $highestColumn = $worksheet->getHighestColumn();
        $highestRow = $worksheet->getHighestRow();

        $excelData = $worksheet->rangeToArray('A1:' . $highestColumn . $highestRow, null, true, false);

        // dd($excelData);

        // $datas = [
        //     'sales' => [],
        //     'return' => [],
        //     'logistics' => []

        // ];

        $datas = array();

        foreach($excelData as $num => $dat){
            if ($num < 1) continue;
            if(!isset($datas[$dat[5]])){
                $datas[$dat[5]] = [];
                $datas[$dat[5]]['sales'] = [];
                $datas[$dat[5]]['return'] = [];
            }

            $dat['product_cost'] = ($dat[14] * 60 / 100 );
            if($dat && $dat[9] == 'Продажа'){
                array_push($datas[$dat[5]]['sales'],$dat);
            }else if($dat && $dat[9] == 'Возврат'){
                array_push($datas[$dat[5]]['return'],$dat);
            }
        }

        return response()->json([
            'status' => true,
            'datas' => $datas
        ],200);
    }
}
