@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.Edit_Configuration') }}
    </div>

    <!--END TITLE & BREADCRUMB PAGE-->

    <!--BOF CONTENT-->
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
            <form action="{{ url('admin/configurations/update') }}" method="post" name="form1" id="form1" enctype="multipart/form-data">
            <input type="hidden" id="_method" name="_method" value="PATCH">
            {!! csrf_field() !!}
            <input type="hidden" name="btnClick" id="btnClick" value="">

            <table class="table table-bordered table-striped table-condensed cf     configuration">
            <thead>
            </thead>
            <tbody>

            @foreach($configurations as $key=>$value)
            <tr>
                <td width="60%" id="title_{{ $value['id'] }}">{{ $value['title'] }}
                    <span class="require"> * </span></br><span class='small'>{{ $value['description'] }}</span>
                </td>

                @if($value['var_type'] =='text')
                <td id="{{ $value['id'] }}">
                    @if($value['varname'] == 'MAIL_PASSWORD' )
                        @php
                        $value['value']   =  decrypt($value['value']);
                        @endphp
                    @endif
                <input type="text" class="displaytextarea" name="site_{{ $value['id'] }}" id="site_{{ $value['id'] }}" value="{{ $value['value'] }} ">
                </td>
                @endif

                @if($value['var_type'] =='list')
                    @if($value['varname'] == 'ELIGIBAL_ORDER_STATUS_FOR_CANCELLATION')
                        <td id="{{ $value['id'] }}">
                            <select name="site_{{ $value['id'] }}[]" id="site_{{ $value['id'] }}" multiple style="height: 50px">
                                @foreach($orderStatus as $orderstat)
                                    <option value="{{ $orderstat->slug }}" @if(in_array($orderstat->slug, explode(',', $value['value']))) {{ 'selected' }} @endif>
                                        {{ $orderstat->name }}
                                    </option>
                                @endforeach
                                </select>
                        </td>
                    @else
                        <td id="{{ $value['id'] }}">
                            <select name="site_{{ $value['id'] }}" id="site_{{ $value['id'] }}">
                            @foreach(explode(",",$value['var_opt_val']) as $listbx)
                                <option value="{{ $listbx }}" @if($value['value']==$listbx) {{ 'selected' }} @endif>
                                    {{ $listbx }}
                                </option>
                            @endforeach
                            </select>
                        </td>
                    @endif
                @endif

                @if($value['var_type'] =='textarea')
                <td id="{{ $value['id'] }}">
                    <textarea name="site_{{ $value['id'] }}" id="site_{{ $value['id'] }}" class="displaytextarea" cols="35" rows="4">{{ $value['value'] }}</textarea>
                </td>
                @endif

                @if($value['var_type'] =='radio')
                <td id="{{ $value['id'] }}">
                    @foreach(explode(",",$value['var_opt_val']) as $radio)
                    <input type="radio" name="site_{{ $value['id'] }}" value="{{ $radio }}"
                    @if($value['value']==$radio) checked @endif>
                        {{ $radio }}
                    @endforeach
                </td>
                @endif
            </tr>
            @endforeach
            </tbody>
            </table>


            <div class="actionbuttons">
                <button type="submit" class="btn btn-primary btn-lg" onclick="setClickVal(2);"> {{ trans('global.save') }}</button>
                <button type="button" class="btn btn-primary btn-lg" onclick="cancelOp();">{{ trans('global.cancel') }}</button>
            </div>


            </form>
        </div>

    </div>
</div>
<!--EOF CONTENT-->
<script type="text/javascript">
function setClickVal(clickVal){
    $('#btnClick').val(clickVal);
}
function cancelOp(){
    $("#_method").val("GET");
    document.form1.action = "{{ url('/admin/') }}";
    document.form1.submit();
}
$( "#form1" ).validate({
    rules: {
        @foreach($configurations as $value)
            site_{{ $value['id'] }} :{ required: true },
        @endforeach
    }
});
$('input[type=radio]').on('ifChecked', function(event){
    var selectedRadio=this.name;
    var inputs = document.getElementsByName(selectedRadio);
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked) {
            var radiovalue= inputs[i].value;
        }
    }
    var getid=selectedRadio.split("_");
    var data = {!! $configurations !!};
    for(i=0;i<data.length;i++){
        if(data[i]['id']==getid[1]){
            if(radiovalue=="Yes"){
                var str = data[i]['is_dependent_on'];
                var res = str.split(",");
                for(var j = 0; j < res.length; j++) {
                    $("#"+res[j]).css('display','');
                    $("#title_"+res[j]).css('display','');
                    $("#"+res[j]).required=true;
                }
            }else{
                var str = data[i]['is_dependent_on'];
                var res = str.split(",");
                for(var j=0;j<res.length;j++){
                    $("#"+res[j]).css('display','none');
                    $("#title_"+res[j]).css('display','none');
                    $("#"+res[j]).required=false;
                }
            }
        }
    }
});

$(document).ready(function() {
    var data = {!! $configurations !!};
    for(var i=0;i<data.length;i++){
        if(data[i]['var_type']=="radio" && data[i]['is_dependent']=="1"){
            if($('input:radio[name=site_'+data[i]["id"]+']:checked').val()=="Yes"){
                var str = data[i]['is_dependent_on'];
                var res = str.split(",");
                if(res.length>0){
                    for(var j = 0; j < res.length; j++) {
                        $("#"+res[j]).css('display','');
                        $("#title_"+res[j]).css('display','');
                        $("#"+res[j]).required=true;
                    }
                }
            }else{
                var str = data[i]['is_dependent_on'];
                var res = str.split(",");
                if(res.length>0){
                    for(var j=0;j<res.length;j++){
                        $("#"+res[j]).css('display','none');
                        $("#title_"+res[j]).css('display','none');
                        $("#"+res[j]).required=false;
                    }
                }
            }
        }
    }
});
</script>

@endsection









