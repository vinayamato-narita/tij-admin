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
            <a class="c-sidebar-nav-link" href="{{route('admin.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                管理ユーザ管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('teacher.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                講師情報管理
            </a>
        </li>
        
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('inquirySubject.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ件名管理
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('faq.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                FAQ管理
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
            <a class="c-sidebar-nav-link" onclick="$('#learning-sub').hasClass('hidden') ?
            $('#learning-sub').removeClass('hidden') : $('#learning-sub').addClass('hidden')">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                学習管理
                <span class="fa fa-chevron-down" style="position: absolute; right: 15px"></span>
            </a>
            <ul class="nav-dropdown-items hidden" id="learning-sub">
                <li class=" c-sidebar-nav-item ">
                    <a class=" c-sidebar-nav-link " href="{{route('text.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        テキスト管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item ">
                    <a class=" c-sidebar-nav-link " href="{{route('lesson.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        レッスン管理
                    </a>
                </li>

                <li class=" c-sidebar-nav-item ">
                    <a class=" c-sidebar-nav-link " href="{{route('course.index')}}">
                        <svg class="c-sidebar-nav-icon">
                            <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                        </svg>
                        コース一覧

                    </a>
                </li>

            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('news.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                お知らせ管理
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('inquiry.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                問い合わせ履歴
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('csv.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                CSV出力
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('comment.index')}}">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-book') }}"></use>
                </svg>
                レッスン単位
            </a>
        </li>
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
