<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="showroom-brand">
            <span class="showroom-mark"><i class="bx bxs-car"></i></span>
            <div class="showroom-brand-copy">
                <h4 class="logo-text">Showroom</h4>
                <span class="showroom-brand-sub">Control desk</span>
            </div>
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
                <div class="parent-icon"><i class='bx bx-user-circle'></i>
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
                <div class="parent-icon"><i class='bx bx-car'></i>
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
            <a href="{{ route('all.expense') }}">
                <div class="parent-icon"><i class='bx bx-receipt'></i>
                </div>
                <div class="menu-title">Expense</div>
            </a>
        </li>

        <li class="menu-label">Reports</li>
        <li>
            <a href="{{ route('all.reports') }}">
                <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i>
                </div>
                <div class="menu-title">Reports</div>
            </a>
        </li>

        <li class="menu-label">Chat</li>
        <li>
            <a href="{{ route('all.chat') }}">
                <div class="parent-icon"><i class='bx bx-message-rounded-dots'></i>
                </div>
                <div class="menu-title">Live Chat</div>
            </a>
        </li>

    </ul>
    <!--end navigation-->
</div>