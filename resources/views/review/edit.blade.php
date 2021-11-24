@extends('layouts.default')
@section('title', '復習編集')
@section('content')
    <review-edit
            :detail-review-url = "{{json_encode(route('review.show', $review->review_id))}}"
            :update-url = "{{json_encode(route('review.update', $review->review_id))}}"
            :review ="{{json_encode($review)}}"
            :delete-action="{{ json_encode(route('review.destroy',  $review->review_id)) }}"
            :list-review-url = "{{json_encode(route('review.index'))}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"


    >

    </review-edit>
@endsection
