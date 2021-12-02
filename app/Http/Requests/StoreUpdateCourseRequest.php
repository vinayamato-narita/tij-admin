<?php

namespace App\Http\Requests;

use App\Enums\CourseTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ret = [
            'displayOrder' => 'required|digits_between:1,1000000000',
            'courseName' => 'required|max:255',
            'courseNameShort' => 'max:255',
            'pointCount' => 'required|digits_between:1,1000000000',
            'amount' => 'required|digits_between:0,1000000000',
            'paypalItemNumber' => 'max:45',
            'isForLMS' => 'required',
            'courseType' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required'
        ];

        if ($this->request->get('courseType') == CourseTypeEnum::GROUP_COURSE) {
            $ret ['minReserveCount'] = 'required|digits_between:1,1000000000';
            $ret ['maxReserveCount'] = 'required|digits_between:1,1000000000|gte:minReserveCount';
            $ret ['decideDate'] = 'required';
            $ret ['reverseEndDate'] = 'required';
            $ret ['courseStartDate'] = 'required';
        }
        return $ret;
    }
}
