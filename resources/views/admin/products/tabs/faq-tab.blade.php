<div class="tab-pane fade" id="v-pills-faq" role="tabpanel" aria-labelledby="v-pills-faq-tab">
    <h3>FAQ</h3>
    <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group">
            <label for="shop_id" class="col-form-label">Question</label>
            <input type="text" class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}"
            name="question" id="selected-question" value="" />
                @if ($errors->has('question'))
                    <div class="invalid-feedback">
                        {{ $errors->first('question') }}
                    </div>
                @endif

                <span class="help-block text-muted">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <div class="form-group">
            <label for="shop_id" class="col-form-label">Answer</label>
            <input type="text" class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}"
            name="answer" id="selected-answer" value="" />
                @if ($errors->has('answer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('answer') }}
                    </div>
                @endif

                <span class="help-block text-muted">{{ trans('cruds.product.fields.shop_helper') }}</span>
        </div>

        <div class="datatable-dashv1-list custom-datatable-overright">
            <table class="table display" id="image_table"
                @if (!isset($product->faqs)) style="display: none;" @endif>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th></th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody id="image-table-body" class="image-table-body">
                    @if (isset($product->faqs))
                            @foreach ($product->faqs as $key=>$faq)
                            <tr>
                                <td class="faq-question-td">
                                    {{$faq->question}}
                                </td>
                                <td class="answer-td" id="answer_ext[{{ $key }}]">
                                    {{$faq->answer}}
                                </td>
                                <td>
                                    <div class="text-nowrap text-theme-color">
                                        <a class="text-theme-color border-0 bg-transparent px-0 delete-faq"
                                        data-faq-id="{{ $faq->id }}"><i
                                            class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

        <input type="hidden" name="tabname" value="faq">
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save">Save</button>
        <button type="submit" name="product_submit" class="btn btn-primary btn-lg" value="save_and_stay">Save &
            Stay</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg">Cancel</a>

    </form>
</div>

