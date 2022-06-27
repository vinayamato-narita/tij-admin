<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    {{-- <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <span class="c-header-toggler-icon"></span>
    </button> --}}
    <a class="c-header-brand d-sm-none" href="#">
        <img class="c-header-brand" src="{{ url('/images/logo.png') }}" width="97" height="46" alt="POOF-LOGO">
    </a>
    <button class="c-header-toggler c-class-toggler d-md-down-none" type="button" data-target="#sidebar"
        data-class="c-sidebar-lg-show" responsive="true">
        <span class="c-header-toggler-icon"></span>
    </button>
    <ul class="c-header-nav ml-auto mr-6">
        <li class="c-header-nav-item d-sm-none">
            <button class="c-header-toggler c-class-toggler" type="button" data-target="#sidebar"
                data-class="c-sidebar-lg-show" responsive="true">
                <span class="c-header-toggler-icon"></span>
            </button>
        </li>
        <li class="c-header-nav-item dropdown d-md-down-none">

            <a class="btn btn-light" href="/logout" style="margin-right:10px "> <svg class="c-icon mr-2">
                    <use xlink:href="{{ url('assets/icons/coreui/free.svg#cui-account-logout') }}"></use>
                </svg><span>Logout</span></a>

        </li>
    </ul>
    <div class="c-subheader">
        <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ホーム</a></li>
            @if (isset($breadcrumbs))
                @foreach ($breadcrumbs as $key => $breadcrumb)
                    @if ($key != count($breadcrumbs) - 1)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active">{{ $breadcrumb['text'] }}</li>
                    @endif
                @endforeach
            @endif
        </ol>
    </div>
</header>
