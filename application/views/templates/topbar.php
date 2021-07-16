<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->

            <!-- #################### -->
            <!-- PERQUERIAN -->
            <?php
            // * catch role_id in seesion userdata from _login
            $role_id = $this->session->userdata('role_id');
            $queryMenu = $this->db->select('user_menu.id, menu')

                // * FROM table user menu
                ->from('user_menu')

                // * JOIN table 'user_access_menu' ON 'id' in table 'user_menu' = 'menu_id' in table 'user_access_menu'
                ->join('user_access_menu', 'user_menu.id = user_access_menu. menu_id')

                // * WHERE 'role_id' in table 'user_access_menu' equal to role_id in session userdata
                ->where('user_access_menu.role_id', $role_id)

                // * ORDER BY 'menu_id' in table 'user_access_menu' ASCENDING
                ->order_by('user_access_menu.menu_id', 'ASC')

                // * get result all data above
                ->get();

            // * make result query data above to array
            $menu = $queryMenu->result_array();
            ?>

            <?php foreach ($menu as $m) : ?>
                <!-- SIAPKAN SUB-MENU SESUAI MENU -->
                <?php

                // * SELECT ALL
                $querySubMenu = $this->db->select('*')

                    // * FROM table 'user_sub_menu'
                    ->from('user_sub_menu')

                    // * WHERE 'menu_id' in table 'user_sub_menu' = 'id' from data query above
                    ->where('menu_id', $m['id'])

                    // * AND 'is_active' in table 'user_sub_menu' = 1
                    // * if SUB MENU = 1 meaning the SUB MENU is active
                    // * if SUB MENU = 0 meaning the SUB MENI isn't active, and it can't be used
                    ->where('is_active', 1)

                    // * get result all data above
                    ->get();

                // * make result query data above to array
                $subMenu = $querySubMenu->result_array();
                ?>
                <!-- ENDPERQUERIAN -->
                <!-- ###################### -->


            <?php endforeach; ?>
            <!-- ################# -->
            <a class="navbar-brand" href="<?= base_url('home'); ?>">
                <img src="<?= base_url('assets/img/icon.png') ?>" width="50" height="30" class="d-inline-block align-top" alt="">
                Jual Rumah
            </a>
            <?php if ($this->session->userdata('role_id') == 1) : ?>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <?php foreach ($menu as $m) : ?>
                            <?php
                            // * SELECT ALL
                            $querySubMenu = $this->db->select('*')
                                ->from('user_sub_menu')
                                ->where('menu_id', $m['id'])
                                ->where('is_active', 1)
                                ->get();
                            $subMenu = $querySubMenu->result_array();
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $m['menu']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <?php foreach ($subMenu as $sm) : ?>
                                        <a class="dropdown-item" href="<?= base_url($sm['url']); ?>">
                                            <!-- <i class="<?= $sm['icon']; ?>"></i> -->
                                            <span><?= $sm['title']; ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <ul class="navbar-nav ml-auto">
                <?php if ($this->session->userdata('role_id') == null) : ?>
                    <div class="navbar">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="nav-item mx-1">
                                <a class="nav-link" href="#" role="button">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                    <div class="navbar">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="nav-item mx-1">
                                <a class="nav-link" href="<?= base_url('home/detailCart'); ?>" role="button">
                                    <i class="fas fa-shopping-cart"></i>
                                    <?php $cart = $this->cart->total_items(); ?>
                                    <?php if ($cart) : ?>
                                        <span class="badge badge-counter badge-danger">
                                            <?= $cart; ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>


                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow mt-2">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline small">
                            <?= $user['name'] ?>
                        </span>
                        <?php if ($this->session->userdata('role_id') == null) : ?>
                            <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/default.png'); ?>">
                        <?php elseif ($this->session->userdata('role_id') == 1) : ?>
                            <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                        <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                            <?php $order = $this->db->get_where('transactions', ['email' => $user['email']]); ?>
                            <?php if ($order) : ?>
                                <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                                <span class="badge badge-counter badge-danger">
                                    <?= $order->num_rows(); ?>
                                </span>
                            <?php else : ?>
                                <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>

                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                        <!-- if user isn't login yet view Login button, if user is login view Logout -->

                        <?php if ($this->session->userdata('role_id') == null) : ?>
                            <a class="dropdown-item" href="<?= base_url('auth'); ?>">
                                <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2"></i>
                                Login
                            </a>
                        <?php elseif ($this->session->userdata('role_id') == 1) : ?>
                            <!-- <a class="dropdown-item" href="<?= base_url('user'); ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                                My Profile
                            </a> -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                Logout
                            </a>
                        <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                            <!-- <a class="dropdown-item" href="<?= base_url('user'); ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                                My Profile
                            </a> -->
                            <?php foreach ($subMenu as $sm) : ?>
                                <a class="dropdown-item" href="<?= base_url($sm['url']); ?>">
                                    <i class="<?= $sm['icon']; ?>"></i>
                                    <span><?= $sm['title']; ?></span>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('order'); ?>">
                                <i class="fas fa-money-check-alt fa-sm fa-fw mr-2"></i>
                                My Order
                                <?php $order = $this->db->get_where('transactions', ['email' => $user['email']]); ?>
                                <?php if ($order) : ?>
                                    <span class="badge badge-counter badge-danger">
                                        <?= $order->num_rows(); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        <?php endif; ?>

                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->