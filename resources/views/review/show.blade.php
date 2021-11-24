@extends('layouts.default')
@section('title', '復習情報表示')
@section('content')
    <review-show
            :edit-review-url = "{{json_encode(route('review.edit', $review->review_id))}}"
            :review ="{{json_encode($review)}}"
    >

    </review-show>
@endsection
