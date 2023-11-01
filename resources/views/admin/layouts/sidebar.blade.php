<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{asset('assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
        </div>
{{--        <div>--}}
{{--            <h3 class="logo-text">Troubleshoot</h3>--}}
{{--        </div>--}}
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{route('dashboard')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li class="menu-label"> Services</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bxs-map-alt'></i>
                </div>
                <div class="menu-title">Zone</div>
            </a>
            <ul>
                <li> <a href="{{route('add.zone')}}"><i class='bx bx-radio-circle'></i>Add New Zone</a>
                </li>
                <li> <a href="{{ route('list.zone') }}"><i class='bx bx-radio-circle'></i>Zone List</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-dots-vertical-rounded'></i>
                </div>
                <div class="menu-title">Category</div>
            </a>
            <ul>
                <li> <a href="{{route('add.category')}}"><i class='bx bx-radio-circle'></i>Add New Category</a>
                </li>
                <li> <a href="{{ route('list.category')  }}"><i class='bx bx-radio-circle'></i>Category List</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-dots-horizontal-rounded'></i>
                </div>
                <div class="menu-title">SubCategory</div>
            </a>
            <ul>
                <li> <a href="{{route('add.subcategory')}}"><i class='bx bx-dots-horizontal-rounded'></i>Add New SubCategory</a>
                </li>
                <li> <a href="{{ route('list.subcategory') }}"><i class='bx bx-radio-circle'></i>SubCategoryList</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cube'></i>
                </div>
                <div class="menu-title">Service</div>
            </a>
            <ul>
                <li> <a href="{{route('add.service')}}"><i class='bx bx-radio-circle'></i>Add New Service</a>
                </li>
                <li> <a href="{{route('list.service')}}"><i class='bx bx-radio-circle'></i>Service List</a>
                </li>
                <li> <a href="{{route('add-extra')}}"><i class='bx bx-radio-circle'></i>Add External Service </a>
                </li>
                <li> <a href="{{route('list-extra')}}"><i class='bx bx-radio-circle'></i> External List</a>
                </li>
            </ul>
        </li>
{{--        <li>--}}
{{--            <a href="javascript:;" class="has-arrow">--}}
{{--                <div class="parent-icon"><i class='bx bx-cart'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Service Package</div>--}}
{{--            </a>--}}
{{--            <ul>--}}
{{--                <li> <a href="{{route('add.subcategory')}}"><i class='bx bx-radio-circle'></i>Add New SubCategory</a>--}}
{{--                </li>--}}
{{--                <li> <a href="{{ route('list.subcategory') }}"><i class='bx bx-radio-circle'></i>SubCategoryList</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="javascript:;" class="has-arrow">--}}
{{--                <div class="parent-icon"><i class='bx bx-cart'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Service Package</div>--}}
{{--            </a>--}}
{{--            <ul>--}}
{{--                <li> <a href=""><i class='bx bx-radio-circle'></i>Add New Service Package</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Service Package List</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        <li class="menu-label"> Booking</li>
        <li>
            <a href="{{route('booking.list','default')}}">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Booking List</div>
            </a>
        </li>
        <li class="menu-label"> User</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-male'></i>
                </div>
                <div class="menu-title">Provider</div>
            </a>
            <ul>
                <li> <a href="{{route('provider.add')}}"><i class='bx bx-radio-circle'></i>Add New Provider</a>
                </li>
                <li> <a href="{{route('provider.list')}}"><i class='bx bx-radio-circle'></i>Provider List</a>
                </li>
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Provider Document List</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Provider Address List</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i> Subscribe Provider List</a>--}}
{{--                </li>--}}
            </ul>
        </li>
{{--        <li>--}}
{{--            <a href="javascript:;" class="has-arrow">--}}
{{--                <div class="parent-icon"><i class='bx bxs-group'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Handyman</div>--}}
{{--            </a>--}}
{{--            <ul>--}}
{{--                <li> <a href="ecommerce-products.html"><i class='bx bx-radio-circle'></i>Add New Serviceman</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Serviceman List</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Pending Serviceman Document List</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Unassigned Serviceman</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i> Subscribe Provider List</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        <li>
            <a href="{{route('user.list')}}">
                <div class="parent-icon"><i class='bx bx-group'></i>
                </div>
                <div class="menu-title">Customer List</div>
            </a>
        </li>
{{--        <li>--}}
{{--            <a href="form-froala-editor.html">--}}
{{--                <div class="parent-icon"><i class='bx bx-code-alt'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">All User  List</div>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="menu-label"> TRANSACTIONS</li>
        <li>
            <a href="{{ route('payment-list','online') }}">
                <div class="parent-icon"><i class='bx bx-wallet-alt'></i>
                </div>
                <div class="menu-title">Payment</div>
            </a>
        </li>
        <li>
            <a href="{{route('payment-list','cashPayment')}}">
                <div class="parent-icon"><i class='bx bx-money'></i>
                </div>
                <div class="menu-title">Cash Payment</div>
            </a>
        </li>
        <li class="menu-label"> Promotion</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-diamond'></i>
                </div>
                <div class="menu-title">Coupon</div>
            </a>
            <ul>
                <li> <a href="{{route('add-coupon')}}"><i class='bx bx-radio-circle'></i>Add New Coupon</a>
                </li>
                <li> <a href="{{route('coupon-list')}}"><i class='bx bx-radio-circle'></i>Coupon List</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-flag'></i>
                </div>
                <div class="menu-title">Campaign</div>
            </a>
            <ul>
                <li> <a href="{{ route('campaign.add') }}"><i class='bx bx-radio-circle'></i>Add Category Campaign</a>
                </li>
                <li> <a href="{{ route('service.campaign.add') }}"><i class='bx bx-radio-circle'></i>Add Service Campaign</a>
                </li>
                <li> <a href="{{ route('campaign.list') }}"><i class='bx bx-radio-circle'></i>Campaign List</a>
                </li>
            </ul>
        </li>
{{--        <li>--}}
{{--            <a href="javascript:;" class="has-arrow">--}}
{{--                <div class="parent-icon"><i class='bx bx-cart'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Ads</div>--}}
{{--            </a>--}}
{{--            <ul>--}}
{{--                <li> <a href="ecommerce-products.html"><i class='bx bx-radio-circle'></i>Add New Ads</a>--}}
{{--                </li>--}}
{{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Ads List</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        <li class="menu-label"> System</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bxs-paint-roll'></i>
                </div>
                <div class="menu-title">Page Settings</div>
            </a>
            <ul>
                <li> <a href="{{route('page-setting','about_us')}}"><i class='bx bx-radio-circle'></i>About Us</a>
                </li>
                <li> <a href="{{route('page-setting','cancellation_policy')}}"><i class='bx bx-radio-circle'></i>Cancellation Policy</a>
                </li>
                <li> <a href="{{route('page-setting','privacy_policy')}}"><i class='bx bx-radio-circle'></i>Privacy Policy</a>

                </li>
                <li> <a href="{{ route('page-setting','refund_policy') }}"><i class='bx bx-radio-circle'></i>Refund Policy</a>
                </li>
                <li> <a href="{{ route('page-setting','terms_and_conditions') }}"><i class='bx bx-radio-circle'></i>Teams And Condition</a>
                </li>


                {{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Provider Address List</a>--}}
                {{--                </li>--}}
                {{--                <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i> Subscribe Provider List</a>--}}
                {{--                </li>--}}
            </ul>
        </li>
{{--        <li>--}}
{{--            <a href="">--}}
{{--                <div class="parent-icon"><i class='bx bx-group'></i>--}}
{{--                </div>--}}
{{--                <div class="menu-title">Api Settings</div>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
    <!--end navigation-->
</div>
