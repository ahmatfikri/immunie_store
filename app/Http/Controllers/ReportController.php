<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api')->except(['index']);
    }

    public function get_reports(Request $request)
    {
        $startDate = date('Y-m-01', strtotime($request->dari));
        $endDate = date('Y-m-t', strtotime($request->sampai));

        $report = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.id_produk')
            ->select(DB::raw('
                nama_barang,
                count(*) as jumlah_dibeli,
                harga,
                SUM(total) as pendapatan,
                SUM(jumlah) as total_qty'))
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->groupBy('id_produk', 'nama_barang', 'harga',)
            ->get();
            
           

        return response()->json([
            'data'=> $report,
            
        ]);
    }

    public function index()
    {
        return view('report.index');
       
    }
}
