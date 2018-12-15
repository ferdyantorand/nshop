
<div class="has-sidebar-left">
    <div class="pos-f-t">
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-dark pt-2 pb-2 pl-4 pr-2">
                <div class="search-bar">
                    <input class="transparent s-24 text-white b-0 font-weight-lighter w-128 height-50" type="text"
                           placeholder="start typing...">
                </div>
                <a href="#" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-expanded="false"
                   aria-label="Toggle navigation" class="paper-nav-toggle paper-nav-white active "><i></i></a>
            </div>
        </div>
    </div>
    <div class="sticky">
        <div class="navbar navbar-expand navbar-dark d-flex justify-content-between bd-navbar blue accent-3">
            <div class="relative">
                <a href="#" data-toggle="offcanvas" class="paper-nav-toggle pp-nav-toggle">
                    <i></i>
                </a>
            </div>
            <!--Top Menu Start -->
            <div class="navbar-custom-menu p-t-10">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="nav-link " data-toggle="collapse" data-target="#navbarToggleExternalContent"
                           aria-controls="navbarToggleExternalContent"
                           aria-expanded="false" aria-label="Toggle navigation">
                            <i class=" icon-search3 "></i>
                        </a>
                    </li>
                    <!-- User Account-->
                    <li class="dropdown custom-dropdown user user-menu">
                        <a href="#" class="nav-link" data-toggle="dropdown">
                            <img src="{{ asset('img/dummy/u2.png') }}" class="user-image" alt="User Image">
                            <i class="icon-more_vert "></i>
                        </a>
                        <div class="dropdown-menu p-4">
                            <div class="row box justify-content-between my-4">
                                <div class="col">
                                    <a href="#">
                                        <i class="icon-apps purple lighten-2 avatar  r-5"></i>
                                        <div class="pt-1">Setting</div>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="#">
                                        <i class="icon-beach_access pink lighten-1 avatar  r-5"></i>
                                        <div class="pt-1">Profile</div>
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="{{ route('admin.logout') }}">
                                    <i class="icon-exit_to_app grey darken-3 avatar  r-5"></i>
                                    <div class="pt-1">Logout</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>