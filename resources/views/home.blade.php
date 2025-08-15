@extends('layouts.admin')
@section('content')
<!-- <div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container-fluid">
        <div class="row mt-4">
            <div class="col-12">
                <div class="main-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="header-button d-md-flex justify-content-between">
                    <div class="button-listing">
                        @php
                            $searchType = Request::get('search_type');
                            if(empty($searchType) && !Request::get('search_range')){
                                $searchType = 'current_year';
                            }
                        @endphp

                        <a href="{{route('admin.home',['search_type' => 'current_day'])}}" class="btn text-decoration-none {{ $searchType === 'current_day' ? ' active' : '' }}">Day</a>
                      <a href="{{route('admin.home',['search_type' => 'current_month'])}}" class=" btn text-decoration-none {{ $searchType === 'current_month' ? ' active' : '' }}"> Month</a>
                      <a href="{{route('admin.home',['search_type' => 'current_year'])}}"  class=" btn text-decoration-none {{ $searchType === 'current_year' ? ' active' : '' }}"> Year</a>
                      <a href="{{route('admin.home',['search_type' => 'previous_day'])}}"  class=" btn text-decoration-none {{ $searchType === 'previous_day' ? ' active' : '' }}"> Day-1</a>
                      <a href="{{route('admin.home',['search_type' => 'previous_month'])}}"  class=" btn text-decoration-none {{ $searchType === 'previous_month' ? ' active' : '' }}">Month-1</a>
                      <a href="{{route('admin.home',['search_type' => 'previous_year'])}}"  class=" btn text-decoration-none {{ $searchType === 'previous_year' ? ' active' : '' }}">Year-1</a>
                    </div>
                    <form id="dateRangeForm" action="{{route('admin.home')}}" >
                    <div class="input-calendar ">
                        <input type="text" name="search_range">
                         <button type="submit" class="calendar-icon"><i class="fal fa-search "></i></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-5">
                <div class="left-side">
                    <div class="notification-wrap">
                        <div class="title">
                            <h4>Orders</h4>
                        </div>
                        <div class="notification-info">
                            <ul>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Pending</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$pendingOrders}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Processing</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$processingOrders}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Shipped</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$shippedOrders}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Delivered</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$deliverdOrders}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Cancelled</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$canceledOrders}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Failed</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$failedOrders}}</span>
                                    </div>
                                </li>

                                {{-- <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Order Refunded</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$refundedOrders}}</span>
                                    </div>
                                </li> --}}

                                 {{-- <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-cart-circle-xmark"></i>
                                        <p>Abandoned Carts</p>
                                    </div>
                                     <div class="notification-num">
                                        <span>{{$abandonedCarts}} </span>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="notification-wrap">
                        <div class="title">
                            <h4>Notification</h4>
                        </div>
                        <div class="notification-info">
                            <ul>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-phone"></i>
                                        <p>Contact Us</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$contactUs}}</span>
                                    </div>
                                </li> --}}
                                {{-- <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-clipboard-list-check"></i>
                                        <p>Request Quote</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>0</span>
                                    </div>
                                </li> --}}
                                {{-- <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-box-check"></i>
                                        <p>Custom Order</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>0</span>
                                    </div>
                                </li>
                                <li>
                                     <div class="notification-list">
                                        <i class="fa-regular fa-message-question"></i>
                                        <p>Enquire</p>
                                     </div>
                                    <div class="notification-num">
                                        <span>{{$enquire}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-gem"></i>
                                        <p>Creative Cuts Enquire</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>0</span>
                                    </div>
                                </li> --}}
                                {{-- <li>
                                    <div class="notification-list">
                                        <i class="fa-solid fa-rotate"></i>
                                        <p>Reorder Enquire</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>0</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    <div class="notification-wrap">
                        <div class="title">
                            <h4>Customers</h4>
                        </div>
                        <div class="notification-info">
                            <ul>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-user-plus"></i>
                                        <p>New Customers</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$customers}}</span>
                                    </div>
                                </li>

                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-user"></i>
                                        <p>Total Customers</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{$customersTotal->count()}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="notification-wrap">
                        <div class="title">
                            <h4>Coupon Stats</h4>
                        </div>
                        <div class="notification-info">
                            <ul>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Total Coupons Issued</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ $totalCouponsIssued }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Coupons Redeemed</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ $couponsRedeemed }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Redemption Rate</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ number_format($redemptionRate,2) }} %</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Average Savings per Coupon</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ number_format($averageSavingsPerCoupon,2) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Total Savings to Customers</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ number_format($totalSavingsToCustomers,2) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification-list">
                                        <i class="fa-regular fa-receipt"></i>
                                        <p>Sales Generated from Coupon Redemptions</p>
                                    </div>
                                    <div class="notification-num">
                                        <span>{{ number_format($salesGenerated,2) }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- @if(count($topPerformingCoupons) > 0) --}}
                    <div class="notification-wrap">
                        <div class="title">
                            <h4>Top Performing Coupons</h4>
                        </div>
                        <div class="notification-info">
                            <ul>
                                @if(count($topPerformingCoupons) > 0)
                                    @foreach ($topPerformingCoupons as $coupon )
                                    <li>
                                        <div class="notification-list">
                                            <i class="fa-regular fa-receipt"></i>
                                            <p>{{$coupon->coupon->code}}</p>
                                        </div>
                                        <div class="notification-num">
                                            <span>{{ $coupon->coupon_count }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                @endif
                        </div>
                    </div>
                    {{-- @endif --}}

                </div>
            </div>

            <div class="col-md-8">
                <div class="right-side">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box">
                                <div class="box-icon" style="background-color: #f2eefcbf;">
                                    <i class="fa-solid fa-bag-shopping" style="color: #845adf;"></i>
                                </div>
                                <div class="box-info">
                                    <h5>{{currency_with_format($totalSale)}}</h5>
                                    <span>Total Sale</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="box">
                                <div class="box-icon" style="background-color: #FC6E3D;;">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                                <div class="box-info">
                                    <h5>{{$totalOrders}}</h5>
                                    <span>Total Orders</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="box">
                                <div class="box-icon" style="background-color: #f2eefcbf;">
                                    <i class="fa-solid fa-box" style="color: #4a1c1c;"></i>
                                </div>
                                <div class="box-info">
                                    <h5>{{$products->count()}}</h5>
                                    <span>Total Products</span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="box">
                                <div class="box-icon" style="background-color: #fef8ecbf;">
                                    <i class="fa-solid fa-user" style="color: #f5b849;"></i>
                                </div>
                                <div class="box-info">
                                    <h5>97</h5>
                                    <span>Total Customers</span>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="title">
                                <h4>Products and Sales</h4>
                            </div>
                            <div class="table-wrap">
                                <ul class="nav nav-pills table-nav-tab-wrap mb-4 pt-4" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active table-nav-tab" id="pills-home-tab" data-toggle="pill"
                                            data-target="#pills-home" type="button" role="tab"
                                            aria-controls="pills-home" aria-selected="true">Recent Order</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link table-nav-tab" id="pills-profile-tab" data-toggle="pill"
                                            data-target="#pills-profile" type="button" role="tab"
                                            aria-controls="pills-profile" aria-selected="false">Best Selling
                                            Product</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link table-nav-tab" id="pills-contact-tab" data-toggle="pill"
                                            data-target="#pills-contact" type="button" role="tab"
                                            aria-controls="pills-contact" aria-selected="false">Most Viewed</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link table-nav-tab" id="pills-category-tab" data-toggle="pill"
                                            data-target="#pills-category" type="button" role="tab"
                                            aria-controls="pills-category" aria-selected="false">Sales By Category</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link table-nav-tab" id="pills-reviews-tab" data-toggle="pill"
                                            data-target="#pills-reviews" type="button" role="tab"
                                            aria-controls="pills-reviews" aria-selected="false">Recent Reviews</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="table-responsive">
                                            <table class="table" id="dashboard-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Customer Name</th>
                                                        <th scope="col">Products</th>
                                                        <th scope="col">Total Tax excl.</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($recentOrders) > 0)
                                                        @foreach ($recentOrders as $order )
                                                        <tr>
                                                        <td>{{$order->customer_name}}</td>
                                                            <td>{{$order->products_count}}</td>
                                                            <td>{{$order->amount}}</td>
                                                            <td> {{ date('M d Y g:i A', strtotime($order->created_at)) }}</td>
                                                            <td>{{$order->order_status}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">No Recent Orders</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                        aria-labelledby="pills-profile-tab">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>

                                                    <tr>
                                                        <th scope="col">Product Id</th>
                                                        <th scope="col">Product Name</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Qty.</th>
                                                        <th scope="col">Sales</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($bestSellingProduct) > 0)
                                                        @foreach ($bestSellingProduct as $products )
                                                        <tr>
                                                            <td>{{$products->product_id}}</td>
                                                            <td>{{$products->product_name}}</td>
                                                            <td>{{$products->category_name}}</td>
                                                            <td>{{$products->product_quantity}}</td>
                                                            <td>{{$products->total_sale}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">No Best Selling Products</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-reviews" role="tabpanel"
                                        aria-labelledby="pills-reviews-tab">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Product Id</th>
                                                        <th scope="col">Product Name</th>
                                                        <th scope="col">Customer Name</th>
                                                        <th scope="col">comment</th>
                                                        <th scope="col">rating</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($recentReviews) > 0)
                                                    @foreach ($recentReviews as $recentReview )
                                                        <tr>
                                                            <td>{{$recentReview->product_id}}</td>
                                                            <td>{{$recentReview->product_name}}</td>
                                                            <td>{{$recentReview->customer_name}}</td>
                                                            <td> {{ (strlen($recentReview->comment) > 50) ? substr($recentReview->comment, 0, 50) . '...' : $recentReview->comment }}</td>
                                                            <td>{{$recentReview->rating}}</td>
                                                            <td id="reviewStatus_{{$recentReview->review_id}}">
                                                                @if($recentReview->is_active)
                                                                    <button class="border-0 text-success bg-transparent btn-active" data-id="{{$recentReview->review_id}}
                                                                "><i class="fa-solid fa-circle-check"></i></button>
                                                                @else
                                                                    <button class="border-0 text-danger bg-transparent btn-inactive" data-id="{{$recentReview->review_id}}"><i class="fa-solid fa-circle-xmark"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        {{-- </a> --}}
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">No Recent Reviews</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                        aria-labelledby="pills-contact-tab">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Product Id</th>
                                                        <th scope="col">Products</th>
                                                        <th scope="col">Views</th>
                                                        <th scope="col">Added to cart</th>
                                                        <th scope="col">Purchased</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($mostViewedProducts) > 0)
                                                    @foreach ($mostViewedProducts as $mostViewedProduct )
                                                    <tr>
                                                        {{-- <td>
                                                            <div class="product-img">
                                                            @if(@isset($mostViewedProduct->gallery[0]) && @isset($mostViewedProduct->gallery[0]['thumbnail']))
                                                                    <img src="{{$mostViewedProduct->gallery[0]['thumbnail']}}" title="{{$mostViewedProduct->name}}" alt="{{$mostViewedProduct->name}}">
                                                                @else
                                                                    <img src="{{asset($mostViewedProduct->image)}}" alt="">
                                                                @endif
                                                            </div>
                                                        </td> --}}
                                                        <td>{{$mostViewedProduct->product_id}}</td>
                                                        {{-- <td>{{$mostViewedProduct->product_name}}</td> --}}
                                                        <td>{{$mostViewedProduct->product->name}}</td>
                                                        <td>{{$mostViewedProduct->view_count}}</td>
                                                        {{-- <td>{{$mostViewedProduct->added_to_cart_count}}</td>
                                                        <td>{{$mostViewedProduct->purchased_count}}</td> --}}
                                                        <td>{{$mostViewedProduct->cart_count}}</td>
                                                        <td>{{$mostViewedProduct->order_count}}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Most Viewed Products</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-category" role="tabpanel"
                                        aria-labelledby="pills-category-tab">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Products</th>
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($salesbyCategory) > 0)
                                                    @foreach ($salesbyCategory as $category )
                                                    <tr>
                                                        <td>{{$category->category_name}}</td>
                                                        <td>{{$category->product_count}}</td>
                                                        <td>{{$category->total_sale}}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">No Sales By Category</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
{{-- <script>
    let table = $('#dashboard-table').DataTable();

</script> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        //var startDate = moment().subtract(1, 'years');
        //var endDate = moment();
        var startDate = new Date('{{$startDate->toDateString()}}');
        var endDate = new Date('{{$endDate->toDateString()}}');
        $('input[name="search_range"]').daterangepicker({
            "showDropdowns": true,
            "startDate": startDate,
            "endDate": endDate
        });

    });


    $(document).on('click', '.btn-active, .btn-inactive', function() {
            var id = $(this).data('id');
            var isActive = $(this).hasClass('btn-active');
            var newStatus = isActive ? 0 : 1;
            updateStatus(id, 'is_active', newStatus);
        });

        function updateStatus(id, field, value) {
            $.ajax({
                url: "{{ route('admin.reviews.updateStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    field: field,
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    if(value == 1){
                        $('#reviewStatus_'+id+' button').removeClass('text-danger');
                        $('#reviewStatus_'+id+' button').addClass('text-success');

                        $('#reviewStatus_'+id+' button').removeClass('btn-inactive');
                        $('#reviewStatus_'+id+' button').addClass('btn-active');

                        $('#reviewStatus_'+id+' button i').removeClass('fa-circle-xmark');
                        $('#reviewStatus_'+id+' button i').addClass('fa-circle-check');

                    }else{
                        $('#reviewStatus_'+id+' button').addClass('text-danger');
                        $('#reviewStatus_'+id+' button').removeClass('text-success');

                        $('#reviewStatus_'+id+' button').addClass('btn-inactive');
                        $('#reviewStatus_'+id+' button').removeClass('btn-active');

                        $('#reviewStatus_'+id+' button i').addClass('fa-circle-xmark');
                        $('#reviewStatus_'+id+' button i').removeClass('fa-circle-check');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

</script>

@endsection
