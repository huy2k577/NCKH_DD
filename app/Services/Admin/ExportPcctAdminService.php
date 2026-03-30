<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class ExportPcctAdminService implements FromCollection, WithHeadings,WithEvents
{
    public function collection()
    {
       $lichThis = DB::table('lich_thi')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt', 'ltptctnt.lich_thi_id', '=', 'lich_thi.id')
                ->join('phong_thi as pt','ltptctnt.phong_thi_id','=','pt.id')
                ->join('lich_thi_phong_thi_lop_thi as ltptlt', 'ltptlt.lich_thi_phong_thi_ca_thi_ngay_thi_id', '=', 'ltptctnt.id')
                ->join('lop as l', 'ltptlt.lop_id', '=', 'l.id')
                ->join('ca_thi_ngay_thi as ctnt', 'ltptctnt.ca_thi_ngay_thi_id', '=', 'ctnt.id')
                ->join('ca_thi', 'ctnt.ca_thi_id', '=', 'ca_thi.id')
                ->join('mon_hoc as mh', 'lich_thi.mon_hoc_id', '=', 'mh.id')
                ->join('ky_thi as kt','kt.id','=','lich_thi.ky_thi_id')
                ->selectRaw('
                    lich_thi.id,
                    ltptctnt.id as ltptctnt_id,    
                    mh.ma_monhoc,
                    mh.ten_monhoc,
                    ctnt.ngay_thi,
                    ca_thi.gio_bat_dau,
                    kt.ten_ky,
                    pt.ten_phong,
                    l.ten_lop,
                    l.id as l_id,
                    so_luong_sinhvien

                ')
                ->orderBy('ctnt.ngay_thi', 'asc')
                ->orderBy('ca_thi.gio_bat_dau', 'asc')
                ->orderBy('mh.ten_monhoc', 'desc')
                ->orderBy('mh.ten_monhoc', 'desc')
                ->orderBy('l.ten_lop', 'asc')
                ->get();
                

            foreach($lichThis as $lt)
                {
                   $lt->so_gv = DB::table('phan_cong_coi_thi as pcct')
                        ->join('giang_vien_ca_thi_ngay_thi as gvctnt', 'pcct.giang_vien_ca_thi_ngay_thi_id', '=', 'gvctnt.id')
                        ->join('giang_vien as gv', 'gvctnt.giang_vien_id', '=', 'gv.id')
                        ->where('pcct.lich_thi_phong_thi_ca_thi_ngay_thi_id', $lt->ltptctnt_id)
                        ->pluck('gv.ho_ten_giangvien')
                        ->toArray();
                        $lt->cb1 = $lt->so_gv[0] ?? '';
                        $lt->cb2 = $lt->so_gv[1] ?? '';
                        $lt->cb3 = $lt->so_gv[2] ?? '';
                        $lt->cb4 = $lt->so_gv[3] ?? '';
                }

            return $lichThis->map(function ($item,$index) {
            return [
                $index + 1,
                $item->ma_monhoc,
                $item->ten_monhoc,
                $item->ten_phong,
                $item->ten_lop,
                $item->gio_bat_dau,
                $item->ngay_thi,
                $item->ten_ky,
                $item->so_luong_sinhvien,
                $item->cb1,
                $item->cb2,
                $item->cb3,
                $item->cb4,
            ];
        });
    }

    public function headings(): array
    {
         return [
            [
                'DANH SÁCH PHÂN CÔNG COI THI' 
            ],
            [
                'STT',
                'Mã MH',
                'Tên môn',
                'Phòng',
                'Lớp',
                'Ca thi',
                'Ngày thi',
                'Kỳ thi',
                'Số lượng',
                'Coi thi', 
                '',
                '',
                ''
            ],
            [   
                '', '', '', '', '', '', '', '', '', 
                'CB1', 'CB2', 'CB3', 'CB4' 
            ]
         ];
    }

   public function registerEvents(): array
{
    return [
        //AfterSheet::class là muốn can thiệp sau khi execl đã được tạo xong
        //$event Là một đối tượng chứa toàn bộ thông tin của cái file Excel đang nằm trong bộ nhớ.
        AfterSheet::class => function(AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            // $event->sheet->getDelegate()  xin quyền điều khiển trực tiếp


            // 1. Thực hiện gộp ô (Giữ nguyên code cũ của bạn)
            $sheet->mergeCells('A1:M1'); 
            foreach (range('A', 'I') as $col) {
                $sheet->mergeCells($col.'2:'.$col.'3');
            }
            $sheet->mergeCells('J2:M2');

            // 2. CĂN GIỮA TOÀN BỘ SHEET (Dữ liệu + Tiêu đề)
            // Lấy dòng cuối cùng có dữ liệu
            $highestRow = $sheet->getHighestRow(); // trả về số của  dòng dài nhất| ví dụ dòng dài nhất là 53 => lấy số 53
            $highestColumn = $sheet->getHighestColumn(); // trả  về chữ cái cột cuối cùng| ví dụ M
            
            $fullRange = 'A1:' . $highestColumn . $highestRow;
            
            //getAlignment() dẫn vào bản điều khiển định dạng căn lề của ô trong execl
            //applyFromArray() thực hiện hàng lọt các thao tác thiết lập căn lề  của các dòng mà không phải gõ từng dòng
            //Thay vì bạn phải viết 5-6 dòng code riêng lẻ như thế này:
            // $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            // $sheet->getStyle('A1')->getAlignment()->setVertical('center');
            // $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
            $sheet->getStyle($fullRange)->getAlignment()->applyFromArray([
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,// căn giữa theo chiều ngang
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,// căn giữa theo chiều dọc
                'wrapText' => false, // Để false để ưu tiên giãn cột thay vì xuống dòng
            ]);

            // 3. TỰ ĐỘNG GIÃN CỘT (AUTO SIZE)
            // Lặp qua tất cả các cột từ A đến M để tự động chỉnh độ rộng
            foreach (range('A', $highestColumn) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true); //getColumnDimension chọn cột cụ thể,setAutoSize tự động đo chữ dài nhất trong cột là bao nhiêu rồi tự rộng vừa khích
            }

            // 4. THÊM VIỀN (BORDERS) CHO TOÀN BỘ BẢNG (Tùy chọn - giúp giống ảnh mẫu hơn)
            $sheet->getStyle('A2:' . $highestColumn . $highestRow)
                ->getBorders()// xin quyền Execl để làm việc đường viền của vùng mà mình đã chọn
                ->getAllBorders() //Kẻ tất cả các đường ngăn cách (trong, ngoài, ngang, dọc)
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);// Kiểu đường kẻ mảnh, phổ biến nhất trong văn bản hành chính.

            // 5. RE-APPLY IN ĐẬM CHO TIÊU ĐỀ (Vì bạn vừa ghi đè style toàn sheet)
            $sheet->getStyle('A1:M3')->getFont()->setBold(true);// Im đậm
            $sheet->getStyle('A1')->getFont()->setSize(14); // cỡ chữ 
        },
    ];
}

    
}