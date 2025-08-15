<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SiteConfiguration;
use App\Http\Controllers\Controller;
use App\Models\OrderStatus;

class ConfigurationController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $configurations = SiteConfiguration::orderBy('option_order')->where('varname' , '!=', 'CGST_MAX_PERCENTAGE')->where('varname' , '!=', 'SGST_MAX_PERCENTAGE')->get();
        $orderStatus = OrderStatus::where('cancel_order',true)->get();
        return view('admin.configuration.edit-configuration',compact('configurations','orderStatus'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $all=$request->all();
        foreach ($all as $key => $value) {
			$id=preg_replace('/\D/', '', $key);
			if($id!=''){
				$config=SiteConfiguration::find($id);
                $this->validate($request,[$key=>'required',],['required'=>$config->title.' is required',]);
		        if($config->varname == 'MAIL_PASSWORD'){
                    $value = encrypt($value);
                }
                $config->value = (is_array($value)) ? implode(',',$value)  : $value;
                // $config->value = $value;
		        $config->update();
			}
		}
        return redirect('/admin/configurations');
    }
}
