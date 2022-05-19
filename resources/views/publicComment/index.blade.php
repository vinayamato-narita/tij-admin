@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', '学習者単位')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        学習者単位
                    </h5>
                </div>
            </div>
            <div class="clear"></div>
            <div class="fade-in">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                    </div>
                                    <div class="col-md-10">
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('publicComment.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                    
                                </div>
                                @if(!$comments->isEmpty())
                                    {{ $comments->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('student_id', '生徒番号')</th>
                                                    <th class="text-left min-width-150">@sortablelink('student_nickname', '生徒のニックネーム')</th>
                                                    <th class="text-left min-width-150">@sortablelink('teacher_nickname', '講師のニックネーム')</th>
                                                    <th class="text-left w-100">@sortablelink('create_date', '作成日時')</th>
                                                    <th class="text-left w-100">@sortablelink('update_date', '更新日時')</th>
                                                    <th class="text-left w-30p">@sortablelink('comment', 'コメント')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($comments as $index => $comment)
                                                    <tr class="eachRow" data-student-id="{{ $comment->student_id }}" 
                                                        data-student-nickname="{{ $comment->student_nickname }}"
                                                        data-teacher-nickname="{{ $comment->teacher_nickname }}"
                                                        data-create-date="{{ date('Y-m-d H:i:s', strtotime($comment->create_date)) }}"
                                                        data-update-date="{{ date('Y-m-d H:i:s', strtotime($comment->update_date)) }}"
                                                        data-comment="{{ $comment->comment }}"
                                                    >
                                                        <td class="text-left status">{{ $comment->student_id }}</td>
                                                        <td class="text-left">{{ $comment->student_nickname }}</td>
                                                        <td class="text-left">{{ $comment->teacher_nickname }}</td>
                                                        <td class="text-left">{{ date('Y-m-d', strtotime($comment->create_date)) }} <br> {{ date('H:i:s', strtotime($comment->create_date)) }}</td>
                                                        <td class="text-left">{{ date('Y-m-d', strtotime($comment->update_date)) }} <br> {{ date('H:i:s', strtotime($comment->update_date)) }}</td>
                                                        <td class="text-left">{{ $comment->comment }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $comments->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                @else
                                    <data-empty></data-empty>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="popup_public_comment" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h5 class="modal-title" id="myModalLabel">
                    <?php echo __('コメント詳細')?>
                </h5>
            </div>
            <div class="modal-body" id="public_comment_content">
                <div class="tableContainer">
                    <div class="row">
                        <div class="col-md-6 flex">
                            <div class="col-md-6"><b>生徒番号</b></div>
                            <div class="col-md-6" id="student_id"></div>
                        </div>
                        <div class="col-md-6 flex">
                            <div class="col-md-6"><b>生徒のニックネーム</b></div>
                            <div class="col-md-6" id="student_nickname"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 flex">
                            <div class="col-md-6"><b>講師のニックネーム</b></div>
                            <div class="col-md-6" id="teacher_nickname"></div>
                        </div>
                        <div class="col-md-6 flex">
                            <div class="col-md-6"><b>作成日時</b></div>
                            <div class="col-md-6" id="create_date"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 flex">
                            <div class="col-md-6"><b>更新日時</b></div>
                            <div class="col-md-6" id="update_date"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-md-12"><b>コメント</b></div>
                        </div>
                        <div class="col-md-9" style="margin-left: -10px">
                            <div class="col-md-12">
                                <pre id="comment"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    table {
        font-size: 11px !important;
    }
    .eachRow:hover {
        background-color: lightyellow !important;
        cursor: pointer;
    }
    .modal-dialog {
        max-width: 650px !important;
    }
    .modal-header {
       display: block !important; 
    }
    .row {
        margin-bottom: 12px;
        margin-right: -10px !important; 
        margin-left: -10px !important; 
    }
    button:focus {
        outline: none !important;
    }
</style>
<script>
    $(document).ready(function() {
        $(".eachRow").dblclick(function(){
            let studentId = $(this).attr('data-student-id');
            let studentNickName = $(this).attr('data-student-nickname');
            let teacherNickName = $(this).attr('data-teacher-nickname');
            let createDate = $(this).attr('data-create-date');
            let updateDate = $(this).attr('data-update-date');
            let comment = $(this).attr('data-comment');

            $("#student_id").text(studentId);
            $("#student_nickname").text(studentNickName);
            $("#teacher_nickname").text(teacherNickName);
            $("#create_date").text(createDate);
            $("#update_date").text(updateDate);
            $("#comment").text(comment);
           
            $("#popup_public_comment").modal();
        });
    });
    
</script>
