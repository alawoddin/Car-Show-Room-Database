<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
     </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        
      
        
        <li class="menu-label">Client</li>
       
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Client Info</div>
            </a>
            <ul>
                <li> <a href="{{ route('client.register') }}"><i class='bx bx-radio-circle'></i>Client Register</a>
                </li>
                <li> <a href="{{ route('capital.transactions') }}"><i class='bx bx-radio-circle'></i>Capital Transactions</a>
                </li>
                <li> <a href="{{ route('user.capital') }}"><i class='bx bx-radio-circle'></i>User Capitals</a>
                </li>
                
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Main</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.purchases') }}"><i class='bx bx-radio-circle'></i>All Purchases</a>
                </li>
                <li> <a href="{{ route('vehicle.status') }}"><i class='bx bx-radio-circle'></i>Vehicle Status</a>
                </li>
                <li> <a href="{{ route('invoice.status') }}"><i class='bx bx-radio-circle'></i>Invoice Status</a>
                </li>
               
            </ul>
        </li>

         

      
     
        <li class="menu-label">Info</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Expense</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.expense') }}"><i class='bx bx-radio-circle'></i>All expense</a>
                </li>
                {{-- <li> <a href="{{ route('vehicle.status') }}"><i class='bx bx-radio-circle'></i>Vehicle Status</a>
                </li>
                <li> <a href="{{ route('invoice.status') }}"><i class='bx bx-radio-circle'></i>Invoice Status</a>
                </li> --}}
               
            </ul>
        </li>


         <li class="menu-label">Reports</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Reports</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.reports') }}"><i class='bx bx-radio-circle'></i>All Report</a>
                </li>
                {{-- <li> <a href="{{ route('vehicle.status') }}"><i class='bx bx-radio-circle'></i>Vehicle Status</a>
                </li>
                <li> <a href="{{ route('invoice.status') }}"><i class='bx bx-radio-circle'></i>Invoice Status</a>
                </li> --}}
               
            </ul>
        </li>

        
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-map-alt"></i>
                </div>
                <div class="menu-title">Maps</div>
            </a>
            <ul>
                <li> <a href="map-google-maps.html"><i class='bx bx-radio-circle'></i>Google Maps</a>
                </li>
                <li> <a href="map-vector-maps.html"><i class='bx bx-radio-circle'></i>Vector Maps</a>
                </li>
            </ul>
        </li>
        
        <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>