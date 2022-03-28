<?php

namespace App\Exports;

use App\Models\AdminInquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\InquiryFlag;
use App\Components\CommonComponent;
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
                $query->where(CommonComponent::escapeLikeSentence('admin_inquiry.inquiry_subject', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('admin_inquiry.user_mail', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_email', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $searchInput));
            });
        }
        $inquiryList = $queryBuilder->get()->map(function($item, $key) {
        	$item['inquiry_flag'] = InquiryFlag::getDescription($item['inquiry_flag']);
        	return $item;
        });

        foreach ($inquiryList as &$item) {
            $item = $this->convertShijis($item);
        }

        return $inquiryList;
    }

    public function headings(): array
    {
        $header = ["問合せ番号", "日時", "問い合わせ件名", "学習者番号", "名前", "メールアドレス", "対応状況", "問い合わせ内容"];
        
        foreach ($header as $item) {
            $item = $this->convertShijis($item);
        }

        return $header;
    }

    private function convertShijis($text) {
        return mb_convert_encoding($text, "SJIS", "UTF-8");
    }
}
