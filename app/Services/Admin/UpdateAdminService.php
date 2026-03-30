<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Http\Requests\UpdateLichThiRequests;
use App\Services\Admin\Import_Excel\PhanPhongService;
use App\Services\Admin\Import_Excel\PhanCongGiangVienService;

class UpdateAdminService
{   

    protected $phanPhongService;
    protected $phanCongService;
    public function __construct(PhanPhongService $phanPhongService,PhanCongGiangVienService $phanCongService, )
    {
        $this->phanPhongService = $phanPhongService;
        $this->phanCongService = $phanCongService;
    }

    public function update(UpdateLichThiRequests $request, string $id)
    {  
        
        $ltptltId = DB::table('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt')
            ->join('lich_thi as lt','lt.id','=','ltptctnt.lich_thi_id')
            ->select('lt.id as lt_id','ltptctnt.id','ltptctnt.ca_thi_ngay_thi_id')
            ->where('ltptctnt.id', $id)
            ->first();

        $monHoc = DB::table('mon_hoc')
            ->where('ma_monhoc', $request->ma_monhoc)
            ->first();

        $phong = DB::table('phong_thi')
            ->where('ten_phong', $request->ten_phong)
            ->first();

        $idca= DB::table('ca_thi')
            ->where('ten_ca',$request->ten_ca)
            ->first();

        $ca_thi_ngay_thi = DB::table('ca_thi_ngay_thi')
            ->where('ca_thi_id', $idca->id)
            ->where('ngay_thi', $request->ngay_thi)
            ->value('id');

        $gvIds = DB::table('giang_vien')
            ->whereIn('ma_giangvien', $request->ma_giangvien)
            ->pluck('id')
            ->toArray();

        DB::beginTransaction(); // Từ đây trở đi, các thao tác INSERT/UPDATE/DELETE tạm thời chưa ghi vĩnh viễn
        try
            {   
                $gv_id=DB::table('phan_cong_coi_thi')
                    ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                    ->pluck('giang_vien_ca_thi_ngay_thi_id')
                    ->toArray();

                DB::table('phan_cong_coi_thi')
                    ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                    ->delete();

                DB::table('diem_danh')
                    ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id )
                    ->delete();
                

                if(!$ca_thi_ngay_thi)
                    {
                        $ca_thi_ngay_thi = DB::table('ca_thi_ngay_thi')
                        ->insertGetId([
                            'ca_thi_id'=>$idca->id,
                            'ngay_thi' =>$request -> ngay_thi,
                            ]);
                    }
                
                $ky_thi=DB::table('ky_thi')
                ->where('ten_ky',$request->ky_loai_thi)
                ->first();

                $updateData = [
                    'mon_hoc_id' => $monHoc->id,
                    'ky_thi_id'  => $ky_thi->id,
                ];
                if ($request->password) {
                    $updateData['password'] = Hash::make(request()->password);
                }
                 
                DB::table('lich_thi')
                ->where('id',$ltptltId->lt_id)
                ->update($updateData);
                
                DB::table('lich_thi_phong_thi_ca_thi_ngay_thi')
                ->where('id',$id)
                ->update([
                    'lich_thi_id' => $ltptltId->lt_id,
                    'phong_thi_id' => $phong->id,
                    'ca_thi_ngay_thi_id' => $ca_thi_ngay_thi,
                ]);

                DB::table('lich_thi_phong_thi_lop_thi')
                ->where('lich_thi_phong_thi_ca_thi_ngay_thi_id', $id)
                ->delete();

                $this->phanPhongService->assignStudents(
                    $monHoc->id,
                    collect([$ltptltId->id]),
                );    
                
                DB::table('giang_vien_ca_thi_ngay_thi')
                    ->whereIn('id',$gv_id)
                    ->where('ca_thi_ngay_thi_id', $ltptltId->ca_thi_ngay_thi_id)
                    ->delete();
                            
                $insertGVCTNT_id=[];
                foreach ($gvIds as $gv)
                    {       
                        $gvctntId=DB::table('giang_vien_ca_thi_ngay_thi')
                            ->insertGetId   ([
                                'giang_vien_id'=> $gv,
                                'ca_thi_ngay_thi_id'=> $ca_thi_ngay_thi,
                            ]);
                            
                            
                            $insertGVCTNT_id []=$gvctntId;
                    }  

                $insertPcct = [];
                foreach ($insertGVCTNT_id as $idgv) {
                                $insertPcct[] = [
                        'lich_thi_phong_thi_ca_thi_ngay_thi_id' => $ltptltId->id,
                        'giang_vien_ca_thi_ngay_thi_id' => $idgv,
                        
                    ];
                }
                DB::table('phan_cong_coi_thi')->insert($insertPcct);

                DB::commit(); // tất cả OK → lưu thật
                return redirect()->route('admin.lichthi.index')
                    ->with('success_update', 'Cập nhập  lịch thi thành công');
            }
        catch(QueryException $e)
            {
                   DB::rollBack(); // có lỗi → hủy hết

                   $message = '';

                        // 🔎 Bắt lỗi duplicate key MySQL (1062)
                        if ($e->errorInfo[1] == 1062) {
                            
                            
                            $errorMessage = $e->getMessage();
                            
                            if (str_contains($errorMessage, 'uq_lich_thi_phong_thi_ca_thi_ngay_thi')) {
                                $message = 'Phòng này đã có lịch thi ở ca này và ngày này.';
                            }
                            elseif (str_contains($errorMessage, 'uq_giang_vien_ca_thi_ngay_thi')) {
                                $message = 'Giảng viên đã được phân công coi thi ở ca này trong ngày này.';
                            }
                            elseif (str_contains($errorMessage, 'uq_giang_vien_ca_thi_ngay_thi')) {
                                $message = 'Giảng viên đã được phân công coi thi ở ca này trong ngày này.';
                            }
                            else {
                                $message = 'Dữ liệu bị trùng (vi phạm ràng buộc unique).';
                            }
                        }
                        else {
                            $message = 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage();
                        }
                    
                    return back()
                        ->withErrors(['database_error_update' => $message])
                        ->withInput();
            }
    }
}