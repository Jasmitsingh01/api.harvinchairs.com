 <div class="tab-pane fade" id="v-pills-combination" role="tabpanel" aria-labelledby="v-pills-combination-tab">
     <h3>Combination</h3>
     <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
         @method('PUT')
         @csrf
         <div class="alert alert-info">
             You can also use the
             <a href="#" class="add-feature-link btn btn-link" data-toggle="modal" data-target="#combinationModal"
                 id="combinationLink" style="user-select: auto;"><i class="icon-external-link-sign">Product Combinations
                     Generator</i></a>
             in order to automatically create a set of combinations.
         </div>
         <div class="form-group">
             <label for="shop_id">Unit Prices</label>
             <select name="unit_id" id="unit_id">
                 {{-- <option value="">Price Units</option> --}}
                 @foreach ($price_units as $unit)
                 <option value="{{ $unit->id }}" {{$product->product_combinations->isNotEmpty() && $product->product_combinations[0]->unit_id  == $unit->id ? 'selected' : '' }}>{{ $unit->title }}</option>
                 @endforeach
             </select>

             <label for="shop_id">Discount Types</label>
             <select id="discount_type" name="discount_type">
                 {{-- <option value="">Discount Types</option> --}}
                 <option value="dollar" {{$product->product_combinations->isNotEmpty() && $product->product_combinations[0]->discount_type  == "dollar" ? 'selected' : '' }}>Dollar</option>
                 <option value="percentage" {{$product->product_combinations->isNotEmpty() && $product->product_combinations[0]->discount_type  == "percentage" ? 'selected' : '' }}>Percentage</option>

             </select>

             &nbsp;&nbsp;
             <button type="button" id="image_link_btn" href="#" data-toggle="modal"
                 data-feature="{{ $product->id }}" data-target="#linkImageModal" class="btn btn-primary">Image
                 Link</button>&nbsp;&nbsp;

             {{-- <button type="button" href="#" class="add-combination-link btn btn-primary"
                                        data-toggle="modal" data-feature="{{ $product->id }}"
                                        data-target="#addCombinationModal" id="addCombinationLink">+ New
                                        Combination</button> --}}

             <div class="btn-group">
                 <button type="button" class="btn btn-primary dropdown-toggle" aria-haspopup="true"
                     aria-expanded="false" id="bulkActionsDropdown">
                     Bulk Actions
                 </button>
                 <div class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                     <a class="dropdown-item" id="bulk_action" href="#" data-action="delete" data-value="1">Delete
                         Combination</a>
                     <a class="dropdown-item" id="bulk_action" href="#" data-action="enabled"
                         data-value="1">Enable
                         Combination</a>
                     <a class="dropdown-item" id="bulk_action" href="#" data-action="enabled"
                         data-value="0">Disable
                         Combination</a>
                     <a class="dropdown-item" id="bulk_update" href="#" data-update="reference_code"
                         data-value="0">Update Reference</a>
                     <a class="dropdown-item" id="bulk_update" href="#" data-update="quantity"
                         data-value="0">Update Stock Qty.</a>
                     <a class="dropdown-item" id="bulk_update" href="#" data-update="price" data-value="0">Update
                         Price (Retail)</a>
                     <a class="dropdown-item" id="bulk_update" href="#" data-update="minimum_quantity"
                         data-value="0">Update MOQ (Retail)</a>
                     {{-- <a class="dropdown-item" id="bulk_update" href="#" data-update="bulk_buy_discount"
                         data-value="0">Update Discount Bulk
                         Buy</a> --}}
                     {{-- <a class="dropdown-item" id="bulk_update" href="#" data-update="bulk_buy_minimum_quantity"
                         data-value="0">Update MOQ
                         (Bulk)</a> --}}
                 </div>
             </div>

             {{-- <button id="bulk-action-button" onclick="performBulkAction()">Perform Bulk Action</button> --}}
         </div>

         <div class="fixed-table-body">
             <table id="prod_comb_table" data-toggle="table" class="table display">
                 <thead style="" class="sticky-head">
                     <tr>
                         <th class=" left">
                             <input type="checkbox" id="select-all">
                         </th>
                         <th class=" left" style="" data-field="0" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Image
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class=" left" style="" data-field="1" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Attribute - value pair
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>

                         <th class=" left" style="" data-field="2" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Reference
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class=" left" style="" data-field="3" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Stock Qty.
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class=" left" style="" data-field="4" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Price.
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class=" left" style="" data-field="4" tabindex="0">
                            <div class="th-inner sortable both">
                                <span class="title_box">Price (without GST).
                                </span>
                            </div>
                            <div class="fht-cell"></div>
                        </th>
                         <th class=" left" style="" data-field="5" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">MOQ.
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         {{-- <th class=" left" style="" data-field="6" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Discount (Bulk).
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class=" left" style="" data-field="7" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">MOQ(Bulk).
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th> --}}
                         <th class=" left" style="" data-field="9" tabindex="0">
                            <div class="th-inner sortable both">
                                <span class="title_box">MaxOQ.
                                </span>
                            </div>
                            <div class="fht-cell"></div>
                        </th>
                         <th class=" left" style="" data-field="8" tabindex="0">
                             <div class="th-inner sortable both">
                                 <span class="title_box">Visible
                                 </span>
                             </div>
                             <div class="fht-cell"></div>
                         </th>
                         <th class="right" style="" data-field="10" tabindex="0">

                         </th>
                     </tr>
                 </thead>
                 <tbody class="sortable-list" id="combination_table">
                     @if ($product->product_combinations->isNotEmpty())
                         @foreach ($product->product_combinations as $combination)
                             <tr data-entry-id="{{ $combination->id }}" id="prod_comb_{{ $combination->id }}"
                                 class="activeshan" draggable="true">
                                 <td class=" left" style="">
                                     <input type="checkbox" class="select-item" data-id="{{ $combination->id }}">
                                 </td>
                                 @if (isset($combination->images))
                                     <td class=" left" style=""><img
                                             src="{{ $combination->images[0]['thumbnail'] }}" alt="Image"
                                             width="50"></td>
                                 @else
                                     <td class=" left" style=""><img
                                             src="{{ asset('images/placeholder.png') }} " alt="Image"
                                             width="50"></td>
                                 @endif
                                 <td class=" left" style="">
                                     {{ $combination->all_combination }}
                                 </td>
                                 <td class=" left" style="">
                                     <input type="text" class="input-combi-ref"
                                         value="{{ $combination->reference_code }}"
                                         name="comb_ref[{{ $combination->id }}]">
                                 </td>
                                 <td class=" left" style="">
                                     <input type="text" style="width: 50px;"
                                         value="{{ $combination->quantity != null ? $combination->quantity : 0 }}"
                                         name="comb_qty[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                         inputmode="numeric" title="Please enter a valid number (digits only)">
                                 </td>
                                 <td class=" left" style="">
                                     <input type="text" style="width: 50px;"
                                         value="{{ $combination->price != null ? $combination->price : 0 }}"
                                         name="comb_price[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                         inputmode="numeric" title="Please enter a valid number (digits only)">
                                 </td>
                                 <td class=" left" style="">
                                    <input type="text" style="width: 50px;"
                                        value="{{ $combination->price_without_gst != null ? $combination->price_without_gst : 0 }}"
                                        name="comb_price_without_gst[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                        inputmode="numeric" title="Please enter a valid number (digits only)">
                                </td>
                                 <td class=" left" style="">
                                     <input type="text" style="width: 50px;"
                                         value="{{ $combination->minimum_quantity != null ? $combination->minimum_quantity : 0 }}"
                                         name="comb_minqty[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                         inputmode="numeric" title="Please enter a valid number (digits only)">
                                 </td>
                                 {{-- <td class=" left" style="">
                                     <input type="text" style="width: 50px;"
                                         value="{{ $combination->bulk_buy_discount != null ? $combination->bulk_buy_discount : 0 }}"
                                         name="comb_bulk_discount[{{ $combination->id }}]"
                                         pattern="[0-9]*([.][0-9]+)?" inputmode="numeric"
                                         title="Please enter a valid number (digits only)">
                                 </td>
                                 <td class="left" style="">
                                     <input type="text" style="width: 50px;"
                                         value="{{ $combination->bulk_buy_minimum_quantity != null ? $combination->bulk_buy_minimum_quantity : 0 }}"
                                         name="comb_minqty_bulk[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                         inputmode="numeric" title="Please enter a valid number (digits only)">
                                 </td> --}}
                                 <td class=" left" style="">
                                    <input type="text" style="width: 50px;"
                                        value="{{ $combination->maximum_quantity != null ? $combination->maximum_quantity : 0 }}"
                                        name="comb_maxqty[{{ $combination->id }}]" pattern="[0-9]*([.][0-9]+)?"
                                        inputmode="numeric" title="Please enter a valid number (digits only)">
                                </td>
                                 <td class="center" style="">
                                     <input type="hidden" name="enabled[{{ $combination->id }}]" value="0">
                                     <div
                                         class="custom-control custom-switch {{ $errors->has('enabled') ? 'is-invalid' : '' }}">
                                         <input type="checkbox" class="custom-control-input"
                                             id="enabled[{{ $combination->id }}]"
                                             name="enabled[{{ $combination->id }}]" value="1"
                                             {{ old('enabled', $combination->enabled) == true ? 'checked' : '' }}>
                                         <label class="custom-control-label"
                                             for="enabled[{{ $combination->id }}]"></label>
                                     </div>
                                 </td>
                                 <td>
                                     <div class="text-nowrap text-theme-color">
                                         <a class="text-theme-color border-0 bg-transparent px-0 delete-combination"
                                             data-combination-id="{{ $combination->id }}"><i
                                                 class="fa-solid fa-trash-can"></i></a>
                                     </div>
                                 </td>
                             </tr>
                         @endforeach
                     @else
                         <tr id="Body_trNoData">
                             <td class="text-center" colspan="13"><i
                                     class="bi bi-exclamation-triangle"></i>&nbsp;No Combinations.
                             </td>
                         </tr>
                     @endif



                 </tbody>

             </table>
         </div>
         <div class="form-group mt-4">
             <label for="out_of_stock">When out of stock</label>
             <div class="form-check">
                 <input class="form-check-input" type="radio" value="1" name="out_of_stock"
                     id="out_of_stock_true"
                     {{ $product->out_of_stock != '0' || old('out_of_stock') != '0' ? 'checked' : '' }}>
                 <label class="form-check-label" for="out_of_stock_true">
                     Allow Orders
                 </label>
             </div>
             <div class="form-check">
                 <input class="form-check-input" type="radio" value="0" name="out_of_stock"
                     id="out_of_stock_false"
                     {{ $product->out_of_stock == '0' || old('out_of_stock') == '0' ? 'checked' : '' }}>
                 <label class="form-check-label" for="out_of_stock_false">
                     Deny Orders
                 </label>
             </div>
         </div>
         <div class="form-group">
             <label for="available_now">Displayed text when in-stock</label>
             <input class="form-control {{ $errors->has('available_now') ? 'is-invalid' : '' }}" type="text"
                 name="available_now" id="available_now"
                 value="{{ old('available_now', $product->available_now) }}">
             @if ($errors->has('available_now'))
                 <div class="invalid-feedback">
                     {{ $errors->first('available_now') }}
                 </div>
             @endif
             <span class="help-block">{{ trans('cruds.product.fields.available_now_helper') }}</span>
         </div>
         {{-- <div class="form-group">
                                    <label for="available_later">Displayed text when backordering is allowed</label>
                                    <input class="form-control {{ $errors->has('available_later') ? 'is-invalid' : '' }}"
                                        type="text" name="available_later" id="available_later"
                                        value="{{ old('available_later', $product->available_later) }}">
                                    @if ($errors->has('available_later'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('available_later') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.product.fields.available_later_helper') }}</span>
                                </div> --}}

         <input type="hidden" name="tabname" value="combination">
         <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
         <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
             Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>

     </form>
 </div>
