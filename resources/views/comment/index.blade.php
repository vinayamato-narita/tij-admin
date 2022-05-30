@php
    use App\Components\SearchQueryComponent;
@endphp
<style>
    #fontSize {
        font-size: 11px !important;
    }
</style>
@extends('layouts.default')
@section('title', 'コメント一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        コメント一覧
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
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('comment.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                </div>
                                @if(!$commentList->isEmpty())
                                    {{ $commentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border" id="fontSize">
                                            <thead>
                                                <tr>
                                                    <th class="text-left" style="width: 89px">@sortablelink('lesson_time', 'レッスン日時') </th>
                                                    <th class="text-left">@sortablelink('student_id', '学習者番号')</th>
                                                    <th class="text-left">@sortablelink('student_nickname', '学習者のニックネーム')</th>
                                                    <th class="text-left">@sortablelink('teacher_nickname', '講師のニックネーム') </th>
                                                    <th class="text-left">@sortablelink('course_name', 'コース')</th>
                                                    <th class="text-left">@sortablelink('teacher_rating', '評価項目1')</th>
                                                    <th class="text-left">@sortablelink('teacher_attitude', '評価項目2')</th>
                                                    <th class="text-left">@sortablelink('teacher_punctual', '評価項目3')</th>
                                                    <th class="text-left">@sortablelink('skype_voice_rating_from_student', '評価項目4')</th>
                                                    <th class="text-left">@sortablelink('comment_from_student_to_office', 'レッスンに対する感想')</th>
                                                    <th class="text-left">@sortablelink('skype_voice_rating_from_teacher', '出（0）欠（1）')</th>
                                                    <th class="text-left">@sortablelink('comment_from_teacher_to_student', '学習者へのコメント')</th>
                                                    <th class="text-left">@sortablelink('comment_from_teacher_to_office', '事務局へのコメント')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($commentList as $index => $item)
                                                @php
                                                    $lessonTime = "";
                                                    if (!empty($item['lesson_starttime']) && !empty($item['lesson_endtime'])) {
                                                        $start = strtotime($item['lesson_starttime']);
                                                        $end = strtotime($item['lesson_endtime']);
                                                        $lessonTime = date('Y-m-d H:i', $start).'~'.date('H:i', $end);   
                                                    }
                                                @endphp
                                                    <tr class="row-comment" data-time="{{ $item['lesson_time'] }}" data-comment="{{ $item }}">
                                                        <td class="text-left">{{ $item['lesson_time'] }}</td>
                                                        <td class="text-left">{{ $item['student_id'] }}</td>
                                                        <td class="text-left">{{ $item['student_nickname'] }}</td>
                                                        <td class="text-left">{{ $item['teacher_nickname'] }}</td>
                                                        <td class="text-left">{{ $item['course_name'] }}</td>
                                                        <td class="text-left">{{ $item['teacher_rating'] }}</td>
                                                        <td class="text-left">{{ $item['teacher_attitude'] }}</td>
                                                        <td class="text-left">{{ $item['teacher_punctual'] }}</td>
                                                        <td class="text-left">{{ Str::limit($item['skype_voice_rating_from_student'], 20) }}</td>
                                                        <td class="text-left">{{ Str::limit($item['comment_from_student_to_office'], 20) }}</td>
                                                        <td class="text-left">{{ Str::limit($item['skype_voice_rating_from_teacher'], 20) }}</td>
                                                        <td class="text-left">{{ Str::limit($item['comment_from_teacher_to_student'], 20) }}</td>
                                                        <td class="text-left">{{ Str::limit($item['comment_from_teacher_to_office'], 20) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $commentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="popup_comment" data-backdrop="static">
    <div class="modal-dialog modal-lg model_list_material" style="width: 700px !important">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">コメント詳細</h4>
            </div>
            <div class="modal-body" id="comment_content">
                <div class="tableContainer" >
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>レッスン日時</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="lessonTime" class="content"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>学習者番号</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="student_id" class="content"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>学習者のニックネーム</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="student_nickname" class="content"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>講師のニックネーム</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="teacher_nickname" class="content"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b>コース名</b>
                                    </div>
                                    <div class="col-md-9 cm-content">
                                        <p id="course_name" class="content"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="color: black; background-color:lightblue; padding:5px;">        
                        <b style="padding-left: 15px;">学習者からのコメント</b>        
                    </div>

                    <div class="col-md-12" style="margin: 10px 0px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>レッスンの内容はわかりやすかったですか。</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="teacher_rating" class="content"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>話しやすい雰囲気づくりをしていましたか。</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="teacher_attitude" class="content"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>説明や練習の時間配分はよかったですか。</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="teacher_punctual" class="content"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>画面などの操作はスムーズでしたか。</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="skype_voice_rating_from_student" class="content"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b>レッスンに対する感想</b>
                                    </div>
                                    <div class="col-md-9 cm-content">
                                        <p style="white-space: pre-line" id="comment_from_student_to_office" class="content"></p>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>
                    <div class="col-md-12" style="color: black; background-color:lightblue; padding:5px;">        
                        <b style="padding-left: 15px;">講師からのコメント</b>        
                    </div>

                    <div class="col-md-12" style="margin: 10px 0px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>出（0）欠（1）</b>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="skype_voice_rating_from_teacher" class="content"></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>            
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b>学習者へのコメント</b>
                                    </div>
                                    <div class="col-md-9 cm-content">
                                        <p style="white-space: pre-line" id="comment_from_teacher_to_student" class="content"></p>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <b>事務局へのコメント</b>
                                    </div>
                                    <div class="col-md-9 cm-content">
                                        <p style="white-space: pre-line" id="comment_from_teacher_to_office" class="content"></p>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
          </div>
      </div>
  </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).on("dblclick", ".row-comment", function() {
        $(".content").text("");
        let lessonTime = $(this).data('time');
        let comment = $(this).data('comment');
        $("#lessonTime").text(lessonTime);
        Object.keys(comment).forEach(function(key) {
          $("#" + key).text(comment[key]);
        });
        $('#popup_comment').modal('show');
    })
</script>
