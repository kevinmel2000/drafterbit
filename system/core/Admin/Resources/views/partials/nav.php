<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url('admin'); ?>"><i class="fa fa-home"></i></a>
            </div>
            <!-- /.navbar-header -->
            <ul id="off-canvas" class="nav navbar-top-links navbar-left">
                <li>
                    <a class="navbar-link" href="<?php echo base_url('admin'); ?>">
                        Dashboard
                    </a>
                </li>
                <li class="dropdown">
                    <a class="navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        Content <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="dropdown-caret">
                          <span class="caret-outer"></span>
                          <span class="caret-inner"></span>
                        </div>
                        <li><a href="<?php echo base_url('admin/pages'); ?>">Pages</a></li>
                        <li><a href="<?php echo base_url('admin/files'); ?>">Files</a></li>
                        <li><a href="<?php echo base_url('admin/blog'); ?>">Blog</a></li>
                    </ul>
                    <!-- /.dropdown -->
                </li>
                <li class="dropdown">
                    <a class="navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        Users <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="dropdown-caret">
                          <span class="caret-outer"></span>
                          <span class="caret-inner"></span>
                        </div>
                        <li><a href="<?php echo base_url('admin/user') ?>">Users</a></li>
                        <li><a href="<?php echo base_url('admin/user/group') ?>">Group</a></li>
                    </ul>
                    <!-- /.dropdown -->
                </li>
                <li>
                    <a class="navbar-link" href="#">
                        Disqus
                    </a>
                </li>
                <li class="dropdown">
                    <a class="navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        Setting <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="dropdown-caret">
                            <span class="caret-outer"></span>
                            <span class="caret-inner"></span>
                        </div>
                        <li><a href="<?php echo base_url('admin/setting/general')?>">General</a></li>
                        <li><a href="<?php echo base_url('admin/setting/appearance')?>">Appearance</a></li>
                    </ul>
                    <!-- /.dropdown -->
                </li>
                <li class="dropdown">
                    <a class="navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        System <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="dropdown-caret">
                          <span class="caret-outer"></span>
                          <span class="caret-inner"></span>
                        </div>
                        <!-- Cache coming soon... don't what to do yet-->
                        <!--<li><a href="<?php echo base_url('assets/admin/system/cache'); ?>">Cache</a></li>-->
                        <li><a href="<?php echo base_url('admin/system/log') ?>">Log</a></li>
                        <li><a href="<?php echo base_url('admin/system/cache') ?>">Cache</a></li>
                    </ul>
                    <!-- /.dropdown -->
                </li>
                <li class="dropdown">
                    <a class="navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        Help <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="dropdown-caret">
                          <span class="caret-outer"></span>
                          <span class="caret-inner"></span>
                        </div>
                        <li><a href="javascript:alert('Coming Soon !')">Help</a></li>
                        <li><a href="javascript:alert('Coming Soon !')">Official Support</a></li>
                        <li><a href="javascript:alert('Coming Soon !')">Documentation Wiki</a></li>
                    </ul>
                    <!-- /.dropdown -->
                </li>
            </ul>

            <ul class="nav navbar-top-links navbar-right user-preferences">
                <li>
                    <a href="/" target="_blank"  class="navbar-link">
                        Front Page
                        <i class="fa fa-external-link"></i>
                    </a>
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class=" navbar-link dropdown-toggle" data-toggle="dropdown" href="#">
                        <img width="20px;" class="profile-avatar" src="<?php echo $userGravatar; ?>" alt="<?php echo $userName ?>"/>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-user">
                        <div class="dropdown-caret">
                            <span class="caret-outer"></span>
                            <span class="caret-inner"></span>
                        </div>
                        <li><a href="#" class="soon">My Profile</a> </li>
                        <li><a href="#" class="soon">Preferences</a> </li>
                        <li><a href="<?php echo base_url('admin/logout'); ?>">Logout</a> </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
    </div>
</nav>