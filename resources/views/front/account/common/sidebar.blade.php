
<div class="d-md-block border-end">
    <div class="card-body">
        <div class="list-group list-group-transparent">
            <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center"><strong>My Profile</strong></a>
            <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action d-flex align-items-center"><strong>My Orders</strong></a>
            <a href="{{ route('account.wishlist') }}" class="list-group-item list-group-item-action d-flex align-items-center"><strong>Wishlist</strong></a>
            <a href="{{ route('account.show-Change-Password-Form') }}" class="list-group-item list-group-item-action d-flex align-items-center"><strong>Change Password</strong></a>
            <a href="{{ route('account.logout') }}" class="list-group-item list-group-item-action d-flex align-items-center"><strong>Logout</strong></a>
        </div>
        <h4 class="subheader mt-4"><strong>Experience</strong></h4>
        <div class="list-group list-group-transparent">
            <a href="#" class="list-group-item list-group-item-action"><strong>Give Feedback</strong></a>
        </div>
    </div>
</div>

{{-- <ul id="account-panel" class="col-4 d-none d-md-block border-end" >
    <li class="nav-item">
        <a href="{{ route('account.profile') }}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-login" aria-expanded="false"><i class="fas fa-user-alt"></i> My Profile</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.orders') }}"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-shopping-bag"></i>My Orders</a>
    </li>
    <li class="nav-item">
        <a href="wishlist.php"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-heart"></i> Wishlist</a>
    </li>
    <li class="nav-item">
        <a href="change-password.php"  class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-lock"></i> Change Password</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.logout') }}" class="nav-link font-weight-bold" role="tab" aria-controls="tab-register" aria-expanded="false"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </li>
</ul> --}}