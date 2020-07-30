<nav id="column-left">
    <div id="profile">
        <div>
          <img src="{{ asset('image/user-icon-profile.png') }}" class="img-circle" width="50" height="50" />
        </div>
        <div style="margin-top: 10px;">
          <h4>{{ Auth::user()->fname." ".Auth::user()->lname}}</h4>
        </div>
    </div>
    <ul id="menu" style="background-color:#636466;">
        <li id="dashboard">
            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a>
        </li>
        @if (in_array('PER1004', session()->get('userPermsData')))
        <li>
            <a class="parent active"><i class="fa fa-user fa-fw"></i> <span>Users</span></a>
            <ul>
              <li><a href="{{ route('users') }}">Users</a></li>
            </ul>
        </li>
        @endif

        @if (in_array('PER1005', session()->get('userPermsData')))
            <li><a href=""><i class="fa fa-shopping-cart fa-fw"></i> <span>Store</span></a></li>
        @endif

        @if (in_array('PER1002', session()->get('userPermsData')))
            <li><a href="{{ route('vendors') }}"><i class="fa fa-building fa-fw"></i> <span>Vendor</span></a></li>
        @endif

        @if (in_array('PER1003', session()->get('userPermsData')))
            <li><a href="{{ route('customers') }}"><i class="fa fa-child fa-fw"></i> <span>Customer</span></a></li>
        @endif

        @if (in_array('PER1006', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-gift fa-fw"></i> <span>Items</span></a>
                <ul>
                    <li><a href="">Item</a></li>
                    <li><a href="">Quick Item</a></li>
                    <li><a href="">Parent Child</a></li>

                </ul>
            </li>
        @endif

        @if (in_array('PER1007', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-sitemap fa-fw"></i> <span>Inventroy</span></a>
                <ul>
                    <li><a href="{{ route('physicalInventroy') }}">New Physical Inventroy </a></li>
                    <li><a href="">Inventroy 2</a></li>
                </ul>
            </li>
        @endif

        @if (in_array('PER1008', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-tags fa-fw"></i> <span>Administration</span></a>
                <ul>
                <li><a href="{{ route('departments') }}">Departments</a></li>
                <li><a href="">Category</a></li>
                </ul>
            </li>
        @endif

        @if (in_array('PER1009', session()->get('userPermsData')))
            <li><a class="parent"><i class="fa fa-bars fa-fw"></i>&nbsp;&nbsp;<span>Reports</span></a>
                <ul>
                <li><a href="">End of Day</a></li>
                <li><a href="">End Of Shift</a></li>
                </ul>
            </li>
        @endif

        @if (in_array('PER1010', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-cog fa-fw"></i> <span>Settings</span></a>
                <ul>
                <li><a href="">Item List Display</a></li>
                <li><a href="">POS Settings</a></li>
                </ul>
            </li>
        @endif

        @if (in_array('PER1011', session()->get('userPermsData')))
            <li><a href=""><i class="fa fa-child fa-fw"></i> <span>Loyalty</span></a></li>
        @endif

        @if (in_array('PER1012', session()->get('userPermsData')))
            <li><a class="parent active"><i class="fa fa-circle-thin"></i> <span>General</span></a>
                <ul>
                <li><a href="">Upc Conversion</a></li>
                <li><a href="">Download</a></li>
                </ul>
            </li>
        @endif

    </ul>
</nav>
