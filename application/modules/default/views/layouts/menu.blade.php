<li class="nav-item {{ empty(uri_segment(1)) ? 'active open' : null }}">
    <a href="{{base_url()}}" class="nav-link ">
        <i class="fa fa-dashboard"></i>
        <span class="title">Dashboard</span>
        @if(empty(uri_segment(1)))
            <span class="selected"></span>
        @endif
    </a>
</li>
<?php
    $datamenu = User::selectraw('users.*, users_menu.*, menu.*, users_menu.menu_id')
    ->leftJoin('users_menu', 'users_menu.user_id', '=', 'users.id')
    ->leftJoin('menu', 'menu.menu_id', '=', 'users_menu.menu_id')
    /*
    ->where('users.company_id', auth_company())
    ->where('users.dep_id', auth_dep())\
    */
    ->where('users.group_id', auth_group_id())
    ->where('users.id', auth_id())
    ->where('users_menu.StatusUsermenu', '1')
    ->where('menu.parent_menu_id', '0')
    ->where('menu.status', '1')
    ->orderBy('menu.menu_code', 'ASC')
    ->get();
    
    if(isset($datamenu)){
        foreach($datamenu as $row){ 
            $datadetailmenu = User::selectraw('users.*, users_menu.*, menu.*, users_menu.menu_id, menu.menu_name AS cmenu_name, menu.menu_link, menu.lang as clang')
            ->leftJoin('users_menu', 'users_menu.user_id', '=', 'users.id')
            ->leftJoin('menu', 'menu.menu_id', '=', 'users_menu.menu_id')
            /*
            ->where('users.company_id', auth_company())
            ->where('users.dep_id', auth_dep())
            */
            ->where('users.group_id', auth_group_id())
            ->where('users.id', auth_id())
            ->where('users_menu.StatusUsermenu', '1')
            ->where('menu.parent_menu_id', $row->menu_id)
            ->where('menu.status', '1')
            ->orderBy('menu.menu_code', 'ASC')
            ->get();
    ?>
    @if($row->lang != "notification")
        <li class="nav-item {{ (uri_segment(1) == $row->lang) ? 'active open' : null }}">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="{{$row->menu_icon}}"></i>
                <span class="title">{{ lang($row->lang) }}</span>
                @if(uri_segment(1) == $row->lang)
                    <span class="selected"></span>
                    <span class="arrow open"></span>    
                @else
                    <span class="arrow"></span>
                @endif
            </a>    
            <ul class="sub-menu">
                @if(isset($datadetailmenu) && count($datadetailmenu) > 0)
                    @foreach($datadetailmenu as $detailrows)
                        <li class="nav-item {{ (uri_segment(2) == $detailrows->menu_link) ? 'active' : null }}">
                            <a href="{{ base_url() .  $row->lang . '/'. $detailrows->menu_link }}" class="nav-link">
                                <i class="fa fa-circle-o nav-link" style="position: absolute;right: 193px;top: 8px;"></i>
                                <span class="title" style="padding-left: 2px">{{lang($detailrows->lang)}}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </li>
    @endif
    <?php } } ?>


<li class="nav-item {{ (uri_segment(1) == "logs") ? 'active open' : null }}">
    <a href="{{base_url()}}logs" class="nav-link ">
        <i class="icon-clock"></i>
        <span class="title">History</span>
        @if(uri_segment(1) == "logs")
        <span class="selected"></span>
        @endif
    </a>
</li>