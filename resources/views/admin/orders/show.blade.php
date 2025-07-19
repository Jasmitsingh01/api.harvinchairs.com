@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="order-detail-container">
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="main-title">
                        <h1>Orders</h1>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-icon" style="background-color: #f2eefcbf;">
                            <i class="fa-solid fa-calendar-days" style="color: #845adf;"></i>
                        </div>
                        <div class="box-info">
                            <h5>Date</h5>
                            <span>{{ getFormatedDate($order->created_at) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box">
                        <div class="box-icon" style="background-color: #FC6E3D;;">
                            <i class="fa-solid fa-cart-shopping" style="color: #26bf94;"></i>
                        </div>
                        <div class="box-info">
                            <h5>Total</h5>
                            <span> {{ currency_with_format($order->paid_total) }}</span>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-3">
                    <div class="box">
                        <div class="box-icon" style="background-color: #fef8ecbf;">
                            <i class="fa-solid fa-message" style="color: #f5b849;"></i>
                        </div>
                        <div class="box-info">
                            <h5>Messages</h5>
                            <span>{{ $order->messages->count() }}</span>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-4">
                    <div class="box">
                        <div class="box-icon" style="background-color: #fff2f9;">
                            <i class="fa-solid fa-box-open" style="color: #eb0055;"></i>
                        </div>
                        <div class="box-info">
                            <h5>Product</h5>
                            <span> {{ $order->products_count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="shipping-address-wrap h-100">
                        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                    data-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Shipping Address</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                    data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                    aria-selected="false">Invoice Address</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <div class="shipping-address-detail">
                                    <h5>Name :</h5>
                                    <span>{{ $order->customer_name ?? '' }}</span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Address :</h5>
                                    <span>
                                        {{ $order->orderBillingAddress->full_address ?? '' }}
                                        {{-- {{ $order->orderShippingAddress->address['city'] ?? '' }} ,
                                        {{ $order->orderShippingAddress->address['state'] ?? '' }} --}}
                                    </span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Postcode :</h5>
                                    <span>{{ $order->orderBillingAddress->postal_code ?? '' }}</span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>State :</h5>
                                    <span>
                                        {{ $order->orderBillingAddress->state ?? '' }}
                                    </span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Number :</h5>
                                    <span>{{ $order->orderBillingAddress->mobile_number ?? '' }}</span>
                                </div>
                                @if(!empty($order->orderBillingAddress->alternate_mobile_number))
                                    <div class="shipping-address-detail">
                                        <h5>Alternate Number :</h5>
                                        <span>{{ $order->orderBillingAddress->alternate_mobile_number ?? '' }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="shipping-address-detail">
                                    <h5>Name :</h5>
                                    <span>{{ $order->customer->first_name ? $order->customer->first_name : $order->customer_name }}</span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Address :</h5>
                                    {{-- @php dd($order->orderBillingAddress->full_address);@endphp --}}
                                    <span> {{ $order->orderBillingAddress->full_address ?? '' }}
                                        {{-- {{ $order->orderBillingAddress->address['city'] ?? '' }} ,
                                        {{ $order->orderBillingAddress->address['state'] ?? '' }} --}}
                                    </span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Postcode :</h5>
                                    <span>{{ $order->orderBillingAddress->postal_code ?? '' }}</span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>State :</h5>
                                    <span>
                                        {{ $order->orderBillingAddress->state ?? '' }}
                                    </span>
                                </div>
                                <div class="shipping-address-detail">
                                    <h5>Number :</h5>
                                    <span>{{ $order->orderBillingAddress->mobile_number ?? '' }}</span>
                                </div>
                                @if(!empty($order->orderBillingAddress->alternate_mobile_number))
                                    <div class="shipping-address-detail">
                                        <h5>Alternate Number :</h5>
                                        <span>{{ $order->orderBillingAddress->alternate_mobile_number ?? '' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-7">
                    <div class="shipping-wrap ">
                        <div class="title">
                            <h4>Shipping</h4>
                        </div>
                        <div class="shipping-table mx-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Carrier</th>
                                            <th scope="col">Shipping Cost</th>
                                            <th scope="col">Tracking number</th>
                                            <th scope="col">Link</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($order->orderCarrier))
                                            <tr id="shipping_table">
                                                <td>{{ optional($order->orderCarrier)->shipping_date ? date('M d Y', strtotime($order->orderCarrier->shipping_date)) : '' }}
                                                </td>
                                                <td>{{ optional($order->orderCarrier->carrierName)->name }}</td>
                                                <td>{{ optional($order->orderCarrier)->shipping_cost }}</td>
                                                <td>{{ optional($order->orderCarrier)->tracking_number }}</td>
                                                <td>{{ optional($order->orderCarrier)->url }}</td>
                                                <td>
                                                    <div class="shipping-link d-md-flex">
                                                        <button class="btn edit-button"><i
                                                                class="fa-solid fa-pen-to-square"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr id="shipping_inputs" style="display:none;">
                                            <td>
                                                <input type="hidden" name="order_id" id="order_id"
                                                    value="{{ $order->id ?? '' }}">
                                                <input type="hidden" name="order_carriers_id" id="order_carriers_id"
                                                    value="{{ optional($order->orderCarrier)->id ?? '' }}">
                                                <input class="form-control" type="date" id="shipping_date"
                                                    name="shipping_date"
                                                    value="{{ optional($order->orderCarrier)->shipping_date ? \Carbon\Carbon::parse($order->orderCarrier->shipping_date)->format('Y-m-d') : '' }}">
                                            </td>
                                            <td>
                                                <select class="form-control" name="carrier" id="carrier">
                                                    <option value="">Select Carrier</option>
                                                    @foreach ($shippingCarriers as $carrier)
                                                        <option value="{{ $carrier->id }}"
                                                            {{ optional($order->orderCarrier)->carrier_id == $carrier->id ? 'selected' : '' }}>
                                                            {{ $carrier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" name="shipping_cost"
                                                    id="shipping_cost"
                                                    value="{{ optional($order->orderCarrier)->shipping_cost ?? '' }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="tracking_number"
                                                    id="tracking_number"
                                                    value="{{ optional($order->orderCarrier)->tracking_number ?? '' }}">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="url"
                                                    id="shipping_url"
                                                    value="{{ optional($order->orderCarrier)->url ?? '' }}">
                                            </td>
                                            <td>
                                                <div class="shipping-link d-md-flex">
                                                    <button class="btn update-button" id="updateShipping">Update</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <div class="customer-wrap h-100">
                        <div class="title">
                            <h4>Customer</h4>
                            <a href="{{ route('admin.orders.downloadInvoice',$order->id) }}">Download Invoice</a>
                        </div>
                        <div class="customer-detail-wrap">
                            <div class="customer-detail">
                                <h5>Name :</h5>
                                <span><a href=""> {{ $order->customer->first_name ?? '' }}</a></span>
                            </div>
                            <div class="customer-detail">
                                <h5>Email :</h5>
                                <span> {{ $order->customer->email ?? '' }}</span>
                            </div>
                            <div class="customer-detail">
                                <h5>Account registered :</h5>
                                <span>{{ getFormatedDate($order->customer->created_at ?? '') }}</span>
                            </div>
                            <div class="customer-detail">
                                <h5>Valid orders placed :</h5>
                                <span class="detail-highlight"> {{ $validOrderPlaced->validOrder ?? '' }}</span>
                            </div>
                            <div class="customer-detail">
                                <h5>Total spent since registration :</h5>
                                <span class="detail-highlight"> {{ currency_with_format($validOrderPlaced->totalSum) ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="status-wrap">
                        <div class="title">
                            <h4>Status</h4>
                        </div>
                        <div class="update-status">
                            <div class="select-status d-flex">
                                @php
                                    $lastStatus = $order->orderStatusHistory->last();
                                @endphp
                                <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
                                <select name="status" id="order_status" required>
                                    <option value="">Select</option>
                                    @foreach ($orderStatuses as $orderStatus)
                                        @if (isset($lastStatus->status) && $lastStatus->status == $orderStatus->name)
                                            @continue
                                        @endif
                                        <option value="{{$orderStatus->slug}}"> {{ $orderStatus->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary update-button" id="orderStatusButton">Update
                                    Status</button>
                                </form>
                            </div>
                        </div>

                        <div class="status-table mx-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach ($order->orderStatusHistory as $history)
                                            <tr>
                                                <td>{{ $history->status }}</td>
                                                <td>{{ $order->customer_name }}</td>
                                                <td colspan="2">{{ getFormatedDateTime($history->created_at) }}</td>
                                                <td><a href="#">Resend Email</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="payment-wrap h-100">
                        <div class="title">
                            <h4>Payment</h4>
                            <div class="title-icon">
                                <a href="#"><i class="fa-solid fa-chevron-up"></i></a>
                                <a href="#"><i class="fa-solid fa-wrench"></i></a>
                                <a href="#"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                        </div>
                        <div class="payment-table mx-3">
                            <div class="table-responsive px-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Reference ID</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Payment Type</th>
                                            {{-- <th scope="col">Invoice</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderPaymentHistory as $paymentHistory)
                                            <tr>
                                                <td>{{ $paymentHistory->created_at }}</td>
                                                <td>{{ $paymentHistory->payment_method }}</td>
                                                <td>{{ $paymentHistory->transaction_id }}</td>
                                                <td>{{ currency_with_format($paymentHistory->amount) }}</td>
                                                <td>{{ $paymentHistory->payment_type }}</td>
                                                {{-- <td>#IN0000{{ $order->id }}</td> --}}
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- <div class="row mt-4">

                <div class="col-md-6">
                    <div class="messages-wrap">
                        <div class="title">
                            <h4>Messages</h4>
                            <div class="title-icon">
                                <a href="#"><i class="fa-solid fa-chevron-up"></i></a>
                                <a href="#"><i class="fa-solid fa-wrench"></i></a>
                                <a href="#"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                        </div>
                        <div class="messanger-name-wrap">
                            @foreach ($order->orderMessage as $message)

                                <p>
                                    {{ $message->created_at->format('M j Y g:iA') }}
                                    - <span
                                        class="messanger-name">{{ $message->customerName->first_name }}</span>
                                    @if ($message->is_private == 1)
                                        <span class="name-private">Private</span>
                                    @else
                                        <span class="name-private">Public</span>
                                    @endif
                                    <br>
                                    <span>{{ $message->custom_message }}</span>
                                </p>
                            @endforeach
                             @if (isset($order->orderMessage[0]))
                                <p>
                                    {{ $order->orderMessage[0]->created_at->format('M j Y g:iA') }}
                                    - <span
                                        class="messanger-name">{{ $order->orderMessage[0]->customerName->first_name }}</span>
                                    @if ($order->orderMessage[0]->is_private == 1)
                                        <span class="name-private">Private</span>
                                    @else
                                        <span class="name-private">Public</span>
                                    @endif
                                    <br>
                                    <span>{{ $order->orderMessage[0]->custom_message }}</span>

                                </p>
                            @endif
                        </div>
                        <div class="messages-table mx-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Email</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Sent Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->messages as $message)
                                            <tr>
                                                <td>{{ $message->subject }}</td>
                                                <td>{{ $message->status }}</td>
                                                <td>{{ $message->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <div class="product-qty">
                        <div class="title">
                            <h4>Product Qty {{$order->products->sum('pivot.order_quantity')}}</h4>
                            <div class="title-icon">
                                <a href="#"><i class="fa-solid fa-chevron-up"></i></a>
                                <a href="#"><i class="fa-solid fa-wrench"></i></a>
                                <a href="#"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                        </div>
                        <div class="product-qty-table mx-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Image</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Unit Price</th>
                                            <th scope="col">Discount</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->products as $product)
                                            <tr>
                                                <td>
                                                    @if (isset($product->attributeCombinations))
                                                        @foreach ($product->attributeCombinations as $combination)
                                                            @if ($combination->id == $product->pivot->product_attribute_id)
                                                                <img src="{{ $combination->image ? $combination->image : $product->image}}" alt="product image"
                                                        style="width: 50px; height: 50px;">
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <img src="{{ $product->image }}" alt="product image"
                                                        style="width: 50px; height: 50px;">
                                                    @endif
                                                </td>
                                                <td>
                                                    <h6>{{ $product->name }}</h6>

                                                    @if (isset($product->attributeCombinations))
                                                        @foreach ($product->attributeCombinations as $combination)
                                                            @if ($combination->id == $product->pivot->product_attribute_id)
                                                                @if(!empty($combination->reference_code))
                                                                    SKU:- {{ $combination->reference_code}} <br/>
                                                                @endif
                                                                {{ $combination->all_combination }}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td>{{ currency_with_format($product->pivot->unit_price) }}</td>
                                                <td>{{ currency_with_format($product->pivot->discounted_price) }}</td>
                                                <td>{{ $product->pivot->order_quantity }}</td>
                                                <td>{{ currency_with_format($product->pivot->subtotal) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Products Total</td>
                                            <td>{{ currency_with_format($order->total - $order->product_discount) }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td colspan="4"></td>
                                            <td>Discount</td>
                                            <td>{{ currency_with_format($order->product_discount) }}</td>
                                        </tr> --}}
                                        @if($order->discount > 0)
                                            <tr>
                                                <td colspan="4"></td>
                                                <td>Coupon {{(isset($order->coupon)) ? '('.$order->coupon->code.')' : '';}}</td>
                                                <td>{{ $order->discount ? currency_with_format($order->discount) : 0.0 }}</td>
                                            </tr>
                                        @endif
                                        {{-- <tr>
                                            <td colspan="3"></td>
                                            <td>Shipping</td>
                                            <td>$ {{ number_format($order->delivery_fee, 2) }}</td>
                                        </tr> --}}
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>Assembly Charges</td>
                                            <td>{{ currency_with_format($order->assembly_charges) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>CGST ({{$order->average_cgst_rate}}%)</td>
                                            <td>{{ currency_with_format($order->total_cgst) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>SGST ({{$order->average_sgst_rate}}%)</td>
                                            <td>{{ currency_with_format($order->total_sgst) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td><span>Total</span></td>
                                            <td><span>{{ currency_with_format($order->paid_total) }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>


    <div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Update Tracking Number</h4>
                </div>
                <form action="{{ route('admin.orders.updateOrderStatus') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="order_id" value="{{ $order->id ?? '' }}">
                        <input type="hidden" name="status" id="order_status" value="shipped">
                        <div class="form-group" name="tracking_number" id="tracking_number">
                            <label for="tracking_number">Tracking Number</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" required>
                        </div>
                        <div class="form-group" name="tracking_url" id="tracking_url">
                            <label for="tracking_url">Tracking URL</label>
                            <input type="text" class="form-control" id="tracking_url" name="tracking_url" required>
                        </div>
                        <div class="form-group" name="Expected Date" id="expected_date">
                            <label for="expected_date">Expected Date</label>
                            <input type="date" class="form-control" id="expected_date" name="expected_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateShipping">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.edit-button').on('click', function() {
                const shipping_table = document.getElementById('shipping_table');
                shipping_table.style.display = 'none';
                const shipping_inputs = document.getElementById('shipping_inputs');
                shipping_inputs.style.display = 'table-row';
            });
            $('#updateShipping').on('click', function() {

                var shippingDate = $('#shipping_date').val();
                var carrierId = $('#carrier').val();
                var shippingCost = $('#shipping_cost').val();
                var trackingNumber = $('#tracking_number').val();
                order_carriers_id
                var shippingUrl = $('#shipping_url').val();
                var orderId = $('#order_id').val();
                var orderCarrierId = $('#order_carriers_id').val();
                var data = {
                    id: orderCarrierId,
                    carrier_id: carrierId,
                    order_id: orderId,
                    shipping_cost: shippingCost,
                    shipping_date: shippingDate,
                    tracking_number: trackingNumber,
                    url: shippingUrl,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.orders.updateShippingCarriers') }}", // Use the named route you defined
                    data: data,
                    success: function(response) {
                        location.reload(); // Display a success message
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                // const shipping_inputs = document.getElementById('shipping_inputs');
                // shipping_inputs.style.display = 'none';
                // const shipping_table = document.getElementById('shipping_table');
                // shipping_table.style.display = 'table-row';
            });
            $('#orderStatusButton').on('click', function() {
                var status = $('#order_status').val();
                //alert(status);
                var orderId = $('#order_id').val();
                var paymentStatus = $('#payment_status').val();
                var data = {
                    order_id: orderId,
                    status: status,
                    _token: '{{ csrf_token() }}'
                };
                if(status == 'shipped'){
                    //open model with tracking number and url inout
                    $('#trackingModal').modal('show');
                }else{
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.orders.updateOrderStatus') }}", // Use the named route you defined
                        data: data,
                        success: function(response) {
                            location.reload(); // Display a success message
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }




            });
        });
    </script>
@endsection
