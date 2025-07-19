<table width="98%" border="1" cellspacing="0" cellpadding="10" style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
    <tbody>
      <tr>
        <td>
          <h4 style="padding:5px 0px;margin:0px;font-size:20px">This mail is send by a customer regarding this product:</h4>
          <p style="margin:5px 2px;background:#d5d5d5;height:1px"></p>
          <p></p>
          <table width="100%" border="1" cellspacing="0" cellpadding="10" style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8" align="center">
            <tbody>
              <tr>
                <td width="19%" align="center">
                  <h6 style="padding:5px 0px;margin:0px;font-size:16px">Product Id</h6>
                </td>
                <td width="21%">
                  <h6 style="padding:5px 0px;margin:0px;font-size:16px">Product Image</h6>
                </td>
                <td width="44%">
                  <h6 style="padding:5px 0px;margin:0px;font-size:16px">Product Name</h6>
                </td>
                <td width="16%">
                  <h6 style="padding:5px 0px;margin:0px;font-size:16px">Price (tax Incl.)</h6>
                </td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF">{{$inquiry->product_id}}</td>
                <td bgcolor="#FFFFFF">
                    <a href="{{$inquiry->url}}" target="_blank" data-saferedirecturl="{{$inquiry->url}}">
                        <img src="{{$inquiry->product_img}}" width="auto" height="100px" class="CToWUd" data-bit="iit"></a></td>
                <td bgcolor="#FFFFFF">
                    <a href="{{$inquiry->url}}" target="_blank" data-saferedirecturl="{{$inquiry->url}}">{{$inquiry->product_title}}</a></td>
                <td bgcolor="#FFFFFF">${{$inquiry->price}}</td>
              </tr>
            </tbody>
          </table>
          <p></p>
          <p><span> <b>Name :</b> {{$inquiry->customer_name}} </span></p>
          <p><span> <b>Email :</b> <a href="mailto:{{$inquiry->customer_email}}" target="_blank">{{$inquiry->customer_email}}</a></span></p>
          <p><b>Subject:</b> {{$inquiry->subject}}</p>
          <p><b>Message:</b> {{$inquiry->message}}</p>
        </td>
      </tr>
    </tbody>
  </table>
