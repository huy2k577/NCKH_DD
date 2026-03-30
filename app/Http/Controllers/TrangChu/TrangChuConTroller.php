<?php

namespace App\Http\Controllers\TrangChu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrangChu\LoginTrangChuService;
use App\Services\TrangChu\LoginPostTrangChuService;
use App\Services\TrangChu\ShowTrangChuService;
use App\Services\TrangChu\VerifyTrangChuService;
use App\Services\TrangChu\KqddTrangChuService;
use App\Services\TrangChu\DssvComatTrangChu;
use App\Services\TrangChu\DssvKhongComatTrangChu;
use App\Services\TrangChu\ExportDssvCmTrangChu;
use App\Services\TrangChu\ExportDssvKhongCmTrangChu;
use App\Http\Requests\TrangChuRequests;
use Maatwebsite\Excel\Facades\Excel;

                  

class TrangChuConTroller extends Controller
{      
    protected $logintrangchuService;
    protected $loginpostrangchuService;
    protected $showtrangchuService;
    protected $verifytrangchuService;
    protected $kqddtrangchuService;
    protected $dssv_comattrangchuService;
    protected $dssv_khongcomattrangchuService;
    

    public function __construct(
        LoginTrangChuService $logintrangchuService,
        LoginPostTrangChuService $loginpostrangchuService, 
        ShowTrangChuService $showtrangchuService, 
        VerifyTrangChuService $verifytrangchuService,
        KqddTrangChuService $kqddtrangchuService, 
        DssvComatTrangChu  $dssv_comattrangchuService,
        DssvKhongComatTrangChu $dssv_khongcomattrangchuService,
        
    )
    {
        $this->logintrangchuService = $logintrangchuService;
        $this->loginpostrangchuService = $loginpostrangchuService;
        $this->showtrangchuService = $showtrangchuService;
        $this->verifytrangchuService= $verifytrangchuService;
        $this->kqddtrangchuService= $kqddtrangchuService;
        $this->dssv_comattrangchuService= $dssv_comattrangchuService;
        $this->dssv_khongcomattrangchuService= $dssv_khongcomattrangchuService;
        


    }

    public function login_get_trangchu()
    {   
        return $this->logintrangchuService->login_get_trangchu();
    }

     public function login_post_trangchu (TrangChuRequests $request)
    {   
        return $this->loginpostrangchuService->login_post_trangchu($request);
    }

    public function show($id)
    {   
        return $this->showtrangchuService->show($id);
    }
     
    public function verify(Request $request,$id)
    {
        return $this->verifytrangchuService->verify($request,$id);
    }

    public function diem_danh_ket_qua($id)
    {
        return $this->kqddtrangchuService->diem_danh_ket_qua($id);
    }

    public function dssv_comat($id)
    {
        return $this->dssv_comattrangchuService->dssv_comat($id);
    }

    public function dssv_khongcomat($id)
    {
        return $this->dssv_khongcomattrangchuService->dssv_khongcomat($id);
    }

    public function export_dssv_comat($id)
    {
        return Excel::download(new ExportDssvCmTrangChu($id), 'lichthi_dssv_comat.xlsx');
    }

    public function export_dssv_khongcomat($id)
    {
        return Excel::download(new ExportDssvKhongCmTrangChu($id), 'lichthi_dssv_khongcomat.xlsx');
    }



}
