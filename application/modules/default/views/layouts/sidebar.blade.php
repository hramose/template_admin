<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel">{{ lang('navigation') }}</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="{{ empty(uri_segment(1)) ? 'active' : null }}">
                <a href="{{ base_url() }}">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">{{ lang('dashboard') }}</span>
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
            <li class="pcoded-hasmenu {{ (uri_segment(1) == $row->lang) ? 'pcoded-trigger' : null }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="feather {{ $row->menu_icon }}"></i></span>
                    <span class="pcoded-mtext">{{ lang($row->lang) }}</span>
                </a>    
                <ul class="pcoded-submenu">
                    @if(isset($datadetailmenu) && count($datadetailmenu) > 0)
                        @foreach($datadetailmenu as $detailrows)
                            <li class="{{ (uri_segment(2) == $detailrows->menu_link) ? 'active' : null }}">
                                <a href="{{ base_url() .  $row->lang . '/'. $detailrows->menu_link }}">
                                    <span class="pcoded-mtext">{{ lang($detailrows->lang) }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>
            <?php 
                }
            }
            ?>

            <li class="{{ (uri_segment(1) == "logs") ? 'active' : null }}">
                <a href="{{ base_url() }}logs">
                    <span class="pcoded-micon"><i class="feather icon-clock"></i></span>
                    <span class="pcoded-mtext">{{ lang('history') }}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>