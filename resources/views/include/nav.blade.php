@php
    use App\Components\AdminUserRightComponent;
@endphp
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand">
        <img class="c-sidebar-brand-full" src="{{ url('images/logo.png') }}" width="55" height="46" alt="CoreUI Logo">
        <img class="c-sidebar-brand-minimized" src="{{ url('images/logo.png') }}" width="55" height="46" alt="CoreUI Logo">
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('dashboard.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-home') }}"></use>
                </svg>
                ホーム
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(PAYMENTHISTORY) }}" href="{{route('paymentHistory.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                支払い履歴管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(STUDENT) }}" href="{{route('student.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                学習者情報管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(TEACHER) }}" href="{{route('teacher.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                講師情報管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link " onclick="$('#schedule-sub').hasClass('hidden') ?
            $('#schedule-sub').removeClass('hidden') : $('#schedule-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                スケジュール管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ (AdminUserRightComponent::getActiveMenu(SCHEDULE) == 'c-active' || AdminUserRightComponent::getActiveMenu(GROUP_LESSON_SCHEDULE) == 'c-active') ? '' : 'hidden' }}" id="schedule-sub">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(SCHEDULE) }}" href="{{route('lessonSchedule.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        プライベートレッスンスケジュール
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(GROUP_LESSON_SCHEDULE) }}" href="{{route('groupSchedule.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        グループレッスンスケジュール
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('lessonStatus.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                レッスン状況管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(GROUP_LESSON_HISTORY) }}" href="{{route('group_lesson_history.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                グループレッスン履歴一覧
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(GROUP_LESSON_RESERVE) }}" href="{{route('groupLessonReserves.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                グループコース申込一覧
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('lessonCancelHistory.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                レッスンキャンセル履歴

            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(ABILITY_TEST_RESULT) }}" href="{{route('abilityTestResult.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                受験済実力テスト一覧
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link " onclick="$('#comment-sub').hasClass('hidden') ?
            $('#comment-sub').removeClass('hidden') : $('#comment-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                コメント管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ (AdminUserRightComponent::getActiveMenu(COMMENT) == 'c-active' || AdminUserRightComponent::getActiveMenu(STUDENT_COMMENT) == 'c-active') ? '' : 'hidden' }}" id="comment-sub">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(COMMENT) }}" href="{{route('comment.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        レッスン単位
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(STUDENT_COMMENT) }}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        学習者単位
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" onclick="$('#learning-sub').hasClass('hidden') ?
            $('#learning-sub').removeClass('hidden') : $('#learning-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                学習管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ (AdminUserRightComponent::getActiveMenu(CATEGORY) == 'c-active' || AdminUserRightComponent::getActiveMenu(COURSE) == 'c-active' || AdminUserRightComponent::getActiveMenu(LESSON) == 'c-active' || AdminUserRightComponent::getActiveMenu(TEXT) == 'c-active' ||
            AdminUserRightComponent::getActiveMenu(PREPARATION) == 'c-active' ||
            AdminUserRightComponent::getActiveMenu(REVIEW) == 'c-active' ||
            AdminUserRightComponent::getActiveMenu(TEST) == 'c-active') ? '' : 'hidden' }}" id="learning-sub">

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(CATEGORY) }}" href="{{route('category.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        カテゴリ管理

                    </a>
                </li>

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(COURSE) }}" href="{{route('course.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        コース一覧

                    </a>
                </li>

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(LESSON) }}" href="{{route('lesson.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        レッスン管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(TEXT) }}" href="{{route('text.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        テキスト管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(PREPARATION) }}" href="{{route('preparation.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                      予習管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(REVIEW) }}" href="{{route('review.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        復習管理
                    </a>
                </li>


                <li class=" c-sidebar-nav-item">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(TEST) }}" href="{{route('test.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        テスト管理
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(REMINDMAIL) }}" href="{{route('remindmail.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                リマインドメール管理

            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link " onclick="$('#bulk-registration-sub').hasClass('hidden') ?
            $('#bulk-registration-sub').removeClass('hidden') : $('#bulk-registration-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                一括登録
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ AdminUserRightComponent::getActiveMenu(COURSE_REGISTRATION) == 'c-active' ? '' : 'hidden' }}" id="bulk-registration-sub">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(COURSE_REGISTRATION) }}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        法人・グループコース登録
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(INQUIRY) }}" href="{{route('inquiry.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ履歴
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(INQUIRYSUBJECT) }}" href="{{route('inquirySubject.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ件名管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(FAQ) }}" href="{{route('faq.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                FAQ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(NEWS) }}" href="{{route('news.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                お知らせ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(ADMINUSER) }}" href="{{route('admin.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                管理ユーザ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link " onclick="$('#zoom-sub').hasClass('hidden') ?
            $('#zoom-sub').removeClass('hidden') : $('#zoom-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                Zoom管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ (AdminUserRightComponent::getActiveMenu(ZOOM_ACCOUNT) == 'c-active' || AdminUserRightComponent::getActiveMenu(ZOOM_SETTING) == 'c-active') ? '' : 'hidden' }}" id="zoom-sub">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(ZOOM_ACCOUNT) }}" href="{{route('zoomAccount.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        Zoomアカウント管理
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(ZOOM_SETTING) }}" href="{{route('zoomSetting.edit')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        Zoom設定
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
