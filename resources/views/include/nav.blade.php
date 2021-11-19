@php
    use App\Components\AdminUserRightComponent;
    $adminUserRights = AdminUserRightComponent::getList();
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
        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([PAYMENTHISTORY], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(PAYMENTHISTORY) }}" href="{{route('paymentHistory.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                支払い履歴管理
            </a>
        </li>
        
        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([STUDENT], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(STUDENT) }}" href="{{route('student.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                生徒情報管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([TEACHER], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(TEACHER) }}" href="{{route('teacher.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                講師情報管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([SCHEDULE], $adminUserRights) }}">
            <a class="c-sidebar-nav-link" href="{{route('lessonSchedule.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                スケジュール管理

            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSONSTATUS], $adminUserRights) }}">
            <a class="c-sidebar-nav-link" href="{{route('lessonStatus.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                レッスン状況管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSONCANCEL], $adminUserRights) }}">
            <a class="c-sidebar-nav-link" href="{{route('lessonCancelHistory.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                レッスンキャンセル履歴

            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([COMMENT], $adminUserRights) }}">
            <a class="c-sidebar-nav-link " onclick="$('#comment-sub').hasClass('hidden') ?
            $('#comment-sub').removeClass('hidden') : $('#comment-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                コメント管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ AdminUserRightComponent::getActiveMenu(COMMENT) == 'c-active' ? '' : 'hidden' }}" id="comment-sub">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{route('comment.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        レッスン単位
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSONCOURSE, TEXT, LESSON], $adminUserRights) }}">
            <a class="c-sidebar-nav-link" onclick="$('#learning-sub').hasClass('hidden') ?
            $('#learning-sub').removeClass('hidden') : $('#learning-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                学習管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items {{ (AdminUserRightComponent::getActiveMenu(CATEGORY) == 'c-active' || AdminUserRightComponent::getActiveMenu(COURSE) == 'c-active' || AdminUserRightComponent::getActiveMenu(LESSON) == 'c-active' || AdminUserRightComponent::getActiveMenu(TEXT) == 'c-active') ? '' : 'hidden' }}" id="learning-sub">

                <li class=" c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSONCOURSE], $adminUserRights) }}">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(CATEGORY) }}" href="{{route('category.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        カテゴリ管理

                    </a>
                </li>

                <li class=" c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSONCOURSE], $adminUserRights) }}">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(COURSE) }}" href="{{route('course.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        コース一覧

                    </a>
                </li>

                <li class=" c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([LESSON], $adminUserRights) }}">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(LESSON) }}" href="{{route('lesson.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        レッスン管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([TEXT], $adminUserRights) }}">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(TEXT) }}" href="{{route('text.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        テキスト管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([PREPARATION], $adminUserRights) }}">
                    <a class=" c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(PREPARATION) }}" href="{{route('preparation.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                      予習管理
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([REMINDMAIL], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(REMINDMAIL) }}" href="{{route('remindmail.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                リマインドメール管理

            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([INQUIRY], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(INQUIRY) }}" href="{{route('inquiry.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ履歴
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([INQUIRYSUBJECT], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(INQUIRYSUBJECT) }}" href="{{route('inquirySubject.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ件名管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([FAQ], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(FAQ) }}" href="{{route('faq.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                FAQ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([NEWS], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(NEWS) }}" href="{{route('news.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                お知らせ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([ADMINUSER], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(ADMINUSER) }}" href="{{route('admin.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                管理ユーザー管理
            </a>
        </li>

        <li class="c-sidebar-nav-item {{ AdminUserRightComponent::checkAdminUserRight([CSVEXPORT], $adminUserRights) }}">
            <a class="c-sidebar-nav-link {{ AdminUserRightComponent::getActiveMenu(CSVEXPORT) }}" href="{{route('csv.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                CSV出力
            </a>
        </li>
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
