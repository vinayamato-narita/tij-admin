@extends('layouts.default')
@section('title', 'コース情報表示')
<?php
use App\Enums\LangType;
?>
@section('content')
    <course-show
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :edit-course-url = "{{json_encode(route('course.edit', $course->course_id))}}"
            :course ="{{json_encode($course)}}"
            :detail-course-url = "{{json_encode(route('course.show',$course->course_id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('course.registerLesson', $course->course_id))}}"
            :list-lesson-url ="{{json_encode(route('course.lesson', $course->course_id))}}"
            :list-lesson-attach-url ="{{json_encode(route('course.lessonAttach', $course->course_id))}}"
            :list-lesson-attach-update-url ="{{json_encode(route('course.lessonAttachUpdate', $course->course_id))}}"
            :list-test-ability-url ="{{json_encode(route('course.test.ability', $course->course_id))}}"
            :register-ability-test-url ="{{json_encode(route('course.test.abilityUpdate', $course->course_id))}}"
            :list-test-course-end-url ="{{json_encode(route('course.test.courseEnd', $course->course_id))}}"
            :register-course-end-test-url ="{{json_encode(route('course.test.courseEndUpdate', $course->course_id))}}"
            :register-video-url ="{{json_encode(route('course.registerVideo', $course->course_id))}}"
            :course-video="{{json_encode($courseVideo)}}"
            :edit-lang-en-url ="{{json_encode(route('course.editLang', [$course->course_id, LangType::EN]))}}"
            :edit-lang-zh-url ="{{json_encode(route('course.editLang', [$course->course_id, LangType::ZH]))}}"
            :add-campaign-url="{{json_encode(route('course.campaignCreate', $course->course_id))}}"
            :course-group-memo ="{{json_encode($courseGroupMemo)}}"
            :url-update-group-memo ="{{ json_encode(route('course.updateGroupMemo')) }}"
    >

    </course-show>
@endsection
