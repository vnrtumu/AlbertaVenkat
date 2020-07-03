<nav id="column-left">
    <div id="profile">
        <div>
          <img src="{{ asset('image/user-icon-profile.png') }}" class="img-circle" width="50" height="50" />
        </div>
        <div style="margin-top: 10px;">
          <h4>Venkat Reddy</h4>
        </div>
      </div>
    <ul id="menu" style="background-color:#636466;">
        <li id="dashboard">
            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a>
        </li>
        <li>
            <a class="parent active"><i class="fa fa-user fa-fw"></i> <span>Users</span></a>
            <ul>
              <li><a href="{{ route('users') }}">Users</a></li>
            </ul>
        </li>
    </ul>
</nav>


 