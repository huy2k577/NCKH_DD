<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LichThiController;
use App\Http\Controllers\TrangChu\TrangChuConTroller;

Route::prefix('admin')
    #->middleware(['auth', 'can:access-admin'])
    ->name('admin.')
    ->group(function () 
    {
        Route::get('login', [LichThiController::class, 'dangnhap_admin'])
            ->name('login');

        Route::post('login', [LichThiController::class, 'verify_admin'])
            ->name('verify');

        Route::get('logout', [LichThiController::class, 'logout'])
            ->name('logout');

        Route::get('export_pcct', [LichThiController::class, 'export'])
            ->name('export_pcct')
            ->middleware('auth','check.permission:pcct_lichthi');

        Route::get('/lichthi/pcct', [LichThiController::class, 'pcct'])
            ->middleware('auth','check.permission:pcct_lichthi')
            ->name('lichthi.pcct');
            
        Route::resource('lichthi',LichThiController::class)
            ->middleware('auth','check.permission:admin_lichthi',);

    }); 

Route::get('/login',[TrangChuConTroller::class,'login_get_trangchu'])
    ->name('login.get.trangchu');

Route::post('/login',[ TrangChuConTroller::class,'login_post_trangchu'])
    ->name('login.post.trangchu');

Route::get('/lichthi/show/{id}', [TrangChuController::class, 'show'])
    ->name('show.trangchu');

Route::post('/lichthi/verify/{id}', [TrangChuController::class, 'verify'])
    ->name('verify.trangchu');

Route::get('/lichthi/diemdanhketqua/{id}', [TrangChuController::class, 'diem_danh_ket_qua'])
    ->name('diemdanhketqua.trangchu')
    ->middleware('check.trangchu');

Route::get('/lichthi/dssv/comat/{id}', [TrangChuController::class, 'dssv_comat'])
    ->name('dssv.comat.trangchu')
    ->middleware('check.trangchu');

Route::get('/lichthi/dssv/khongcomat/{id}', [TrangChuController::class, 'dssv_khongcomat'])
    ->name('dssv.khongcomat.trangchu')
    ->middleware('check.trangchu');

Route::get('/lichthi/export_dssv_comat/{id}', [TrangChuController::class, 'export_dssv_comat'])
    ->name('export.dssv.comat.trangchu')
    ->middleware('check.trangchu');

Route::get('/lichthi/export_dssv_khongcomat/{id}', [TrangChuController::class, 'export_dssv_khongcomat'])
    ->name('export.dssv.khongcomat.trangchu')
    ->middleware('check.trangchu');