<?php
require_once __DIR__ . '/../includes/auth.php';

redirect_to(current_admin() ? 'admin/dashboard.php' : 'admin/login.php');

