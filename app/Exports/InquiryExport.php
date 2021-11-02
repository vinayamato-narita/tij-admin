<?php

namespace App\Exports;

use App\Models\AdminInquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\InquiryFlag;
use DB;

class InquiryExport implements FromCollection, WithHeadings
{
	protected $searchInput;

    public function __construct($searchInput)
    {
       $this->searchInput = $searchInput;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$queryBuilder = AdminInquiry::leftJoin('student', 'student.student_id', '=', 'admin_inquiry.user_id')
        	->select('admin_inquiry.inquiry_id as inquiry_id', 'inquiry_date', 'inquiry_subject', 'admin_inquiry.user_id as student_id', 'student.student_name as student_name', DB::raw('ifnull(admin_inquiry.user_mail,student.student_email) as j_student_email'), 'inquiry_flag', 'inquiry_body');
        	
        $searchInput = $this->searchInput;
        
        if (isset($this->searchInput)) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($searchInput) {
                $query->where($this->escapeLikeSentence('admin_inquiry.inquiry_subject', $searchInput))
                    ->orWhere($this->escapeLikeSentence('admin_inquiry.student_email', $searchInput))
                    ->orWhere($this->escapeLikeSentence('student.student_email', $searchInput))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $searchInput));
            });
        }
        $inquiryList = $queryBuilder->get()->map(function($item, $key) {
        	$item['inquiry_flag'] = InquiryFlag::getDescription($item['inquiry_flag']);
        	return $item;
        });
        return $inquiryList;
    }

    public function headings(): array
    {
        return ["問合せ番号", "日時", "問い合わせ件名", "生徒番号", "名前", "メールアドレス", "対応状況", "問い合わせ内容"];
    }

    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mb_trim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_
        return [[$column, 'LIKE', (($before) ? '%' : '') . $result . (($after) ? '%' : '')]];
    }

    public function mb_trim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);
        return $ret;
    }
}
