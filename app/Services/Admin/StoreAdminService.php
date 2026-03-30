<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LichThiImport;
use App\Services\Admin\Import_Excel\LichThiService;
use Illuminate\Database\QueryException;

class StoreAdminService
{
    public function store(Request  $request)
    {   
        try {

                $request->validate([
                    'file' => 'required|mimes:xlsx,xls'
                ]);

                DB::beginTransaction();

                $import = new LichThiImport(app(LichThiService::class));

                Excel::import($import, $request->file('file'));

                $errors = $import->getRowErrors();

                if (!empty($errors)) {
                    DB::rollBack();
                    return back()->with('errors', $errors);
                }

                DB::commit();

                return back()->with('success', 'Import thành công');

            } catch (QueryException $e) {

                DB::rollBack();
            }
    }           
}