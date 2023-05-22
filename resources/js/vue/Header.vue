<template>
  <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
          aria-expanded="false">
          <img src="storage/images/profile/user-1.png" alt="" width="35" height="35" class="rounded-circle">
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
          <div class="message-body">
            <!-- <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
              <i class="ti ti-user fs-6"></i>
              <p class="mb-0 fs-3">My Profile</p>
            </a> -->
            <a href="/logout_panel" class="d-flex align-items-center gap-2 dropdown-item">
              <i class="ti ti-logout fs-6 text-danger"></i>
              <strong class="text-danger">Logout</strong>
            </a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>