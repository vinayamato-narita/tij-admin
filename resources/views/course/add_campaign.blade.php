@extends('layouts.default')
@section('title', 'キャンペーン追加')
@section('content')
    <course-add-campaign
            :course="{{json_encode($course)}}"
            :url-action="{{json_encode(route('course.addCampaignStore', $course->course_id))}}"
            :url-course-detail="{{json_encode(route('course.show', $course->course_id))}}"
            :exist-campaign-datetime="{{json_encode(route('course.existCampaignDatetime', $course->course_id))}}"


    >

    </course-add-campaign>

@endsection
