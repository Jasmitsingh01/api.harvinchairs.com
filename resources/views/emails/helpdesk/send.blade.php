<table width="98%" border="1" cellspacing="0" cellpadding="10" style="border:1px solid #d6d4d5;border-collapse:collapse;background-color:#f8f8f8">
    <tbody>
      <tr>
        <td>
          <h4 style="padding:5px 0px;margin:0px;font-size:20px">This mail is send by a customer regarding help desk:</h4>
          <p style="margin:5px 2px;background:#d5d5d5;height:1px"></p>
          <p></p>
          <p></p>
          @foreach ($payload as $key=>$value)
          <p><span> <b>{{$key}} :</b> {{$value}} </span></p>
          @endforeach
        </td>
      </tr>
    </tbody>
  </table>
