<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Requests\UpdateLichThiRequests;
use App\Services\Admin\Import_Excel\PhanPhongService;
use App\Services\Admin\Import_Excel\PhanCongGiangVienService;
use App\Services\Admin\IndexAdminService;
use App\Services\Admin\StoreAdminService;
use App\Services\Admin\ShowAdminService;
use App\Services\Admin\EditAdminService;
use App\Services\Admin\UpdateAdminService;
use App\Services\Admin\DestroyAdminService;
use App\Services\Admin\VerifyAdminService;
use App\Services\Admin\LogoutAdminService;
use App\Services\Admin\PcctAdminService;
use App\Services\Admin\ExportPcctAdminService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller as BaseController;

class LichThiController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    protected $phanPhongService;
    protected $phanCongService;
    protected $indexService;
    protected $storeService;
    protected $showService;
    protected $editServiec;
    protected $updateServiec;
    protected $destroyServiec;
    protected $verifyServiec;
    protected $logoutServiec;
    protected $pcctServiec;
   

    public function __construct(
        PhanPhongService $phanPhongService,
        PhanCongGiangVienService $phanCongService,
        IndexAdminService $indexService,
        StoreAdminService $storeService,
        ShowAdminService $showService, 
        EditAdminService $editServiec,
        UpdateAdminService $updateServiec,
        DestroyAdminService $destroyServiec,
        VerifyAdminService $verifyServiec, 
        LogoutAdminService $logoutServiec,
        PcctAdminService $pcctServiec, 
        )
    {   
        $this->phanPhongService = $phanPhongService;
        $this->phanCongService = $phanCongService;
        $this->indexService = $indexService;
        $this->storeService = $storeService;
        $this->showService= $showService;
        $this->editServiec= $editServiec;
        $this->updateServiec= $updateServiec;
        $this->destroyServiec= $destroyServiec;
        $this->verifyServiec= $verifyServiec;
        $this->logoutServiec= $logoutServiec;
        $this->pcctServiec= $pcctServiec;
        

        $this->middleware('check.permission:index_lichthi')->only(['index']);
        $this->middleware('check.permission:view_lichthi')->only(['show']);
        $this->middleware('check.permission:create_lichthi')->only(['create','store']);
        $this->middleware('check.permission:edit_lichthi')->only(['edit','update']);
        $this->middleware('check.permission:delete_lichthi')->only(['destroy']);
        

    }
  
    public function index()
    {       
        return $this->indexService->index();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        return view('admin.lichthi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request  $request)
    {   
       return $this->storeService->store($request);
    }
   
    public function show(string $id)
    {
        return $this->showService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        return $this->editServiec->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLichThiRequests $request, string $id)
    {  
        return $this->updateServiec->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->destroyServiec->destroy($id);
    }

    public function dangnhap_admin()
    {
        return view('admin.lichthi.dangnhap_admin');
    }

    public function verify_admin(Request $request)
    {
        return $this->verifyServiec->verify_admin($request); 
    }

    public function logout(Request $request)
    {
        return $this->logoutServiec->logout($request);
    }

    public function pcct()
    {   
        return $this->pcctServiec->pcct();
    }

    public function export() 
    {
        return Excel::download(new ExportPcctAdminService, 'phan_cong_coi_thi.xlsx');
    }

}
