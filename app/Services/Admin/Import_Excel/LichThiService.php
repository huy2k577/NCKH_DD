<?php

namespace App\Services\Admin\Import_Excel;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\Import_Excel\DatabaseExceptionHandler;
class LichThiService
{
    protected LichThiValidator $validator;
    protected LichThiCreator $creator;
    protected PhanPhongService $phanPhongService;
    protected PhanCongGiangVienService $phanCongService;
    protected CaThiNgayThi $cathingaythi;

    public function __construct(
        LichThiValidator $validator,
        LichThiCreator $creator,
        PhanPhongService $phanPhongService,
        PhanCongGiangVienService $phanCongService,
        CaThiNgayThi $cathingaythi,
    ) 
    {
        $this->validator = $validator;
        $this->creator = $creator;
        $this->phanPhongService = $phanPhongService;
        $this->phanCongService = $phanCongService;
        $this->cathingaythi = $cathingaythi;
    }

    public function handle(array $data): int
    {   
        try 
        {   
            
            
            return DB::transaction(function () use ($data) {

                
                $this->validator->validate($data);
                
                $monHoc = DB::table('mon_hoc')
                    ->where('ma_monhoc', $data['ma_monhoc'])
                    ->first();


                $ca = DB::table('ca_thi')
                    ->where('ten_ca', $data['ca_thi'])
                    ->first();


                $ky_thi = DB::table('ky_thi')
                    ->where('ten_ky', $data['ten_ky'])
                    ->first();

                   

                $ca_thi_ngay_thi= $this->cathingaythi->cathingaythi(
                    $ca->id,
                    $data['ngay_thi']
                );
                

                $lichThiId = $this->creator->create(
                    $data,
                    $monHoc->id,
                    $ky_thi->id,
                );

             
                $lichThiPhongIds = $this->phanPhongService->createRooms(
                    $lichThiId,
                    $data['phong_thi'],
                    $ca_thi_ngay_thi
                );

             
                $this->phanPhongService->assignStudents(
                    $monHoc->id,
                    $lichThiPhongIds
                );

                
                $this->phanCongService->phancongTheoPhong(
                    $lichThiPhongIds,
                    $data['ma_giangvien'],
                    $ca_thi_ngay_thi
                );

                return $lichThiId;
            });
        }
        catch(QueryException $e)
        {   
            
            $message = DatabaseExceptionHandler::handle($e);
            throw new \Exception($message);
        }
    }
}