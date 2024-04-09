<!doctype html>
<html lang="en-US">
   <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
      <title>Reset Password Email Template</title>
      <meta name="description" content="Reset Password Email Template.">
      <style type="text/css">
         a:hover {text-decoration: underline !important;}
      </style>
   </head>
   <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px;" leftmargin="0">
      <!--100% body table-->
      <table border="0" cellspacing="0" cellpadding="0" align="center" id="m_1253343828888469271email_table" style="border-collapse:collapse">
         <tbody>
            <tr>
               <td id="m_1253343828888469271email_content" style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;background:#ffffff">
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                     <tbody>
                        <tr>
                           <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="1" colspan="3" style="line-height:1px"><span style="color:#ffffff;font-size:1px;opacity:0">Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu Plats của bạn.</span></td>
                        </tr>
                        <tr>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                           <td>
                              <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                 <tbody>
                                    <tr>
                                       <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                       <td width="32" align="left" valign="middle" style="height:32;line-height:0px"><img width="32" src="https://plats.network/img/logo-white.svg" height="32" style="border:0" class="CToWUd" data-bit="iit"></td>
                                       <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                                       <td width="100%"><span style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:19px;line-height:32px;color:#1877f2"></span></td>
                                    </tr>
                                    <tr style="border-bottom:solid 1px #e5e5e5">
                                       <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                           <td>
                              <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                 <tbody>
                                    <tr>
                                       <td height="4" style="line-height:4px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                       @yield('content')
                                    </tr>
                                    <tr>
                                       <td height="50" style="line-height:50px">&nbsp;</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                           <td>
                              <table border="0" width="100%" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse">
                                 <tbody>
                                    <tr style="border-top:solid 1px #e5e5e5">
                                       <td height="19" style="line-height:19px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                       <td style="font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:11px;color:#8a8d91;line-height:16px;font-weight:400">
                                          <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;color:#8a8d91;text-align:center;font-size:12px;font-weight:400;font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif">
                                             <tbody>
                                                <tr>
                                                   <td align="center" style="font-size:12px;font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;color:#8a8d91;text-align:center;font-weight:400;padding-bottom:6px">từ</td>
                                                </tr>
                                                <tr>
                                                   <td align="center" style="font-size:12px;font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;color:#8a8d91;text-align:center;font-weight:400;padding-top:6px;padding-bottom:6px"><img width="74" alt="Plats" height="22" src="https://plats.network/img/logo-white.svg" style="border:0" class="CToWUd" data-bit="iit"></td>
                                                </tr>
                                                <tr>
                                                   <td align="center" style="font-size:12px;font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;color:#8a8d91;text-align:center;font-weight:400;padding-top:6px;padding-bottom:6px">© Plats Network Platforms, Inc., All rights reserved.</td>
                                                </tr>
                                                <tr>
                                                   <td align="center" style="font-size:12px;font-family:Roboto-Regular,Roboto,-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;color:#8a8d91;text-align:center;font-weight:400;padding-top:6px">Thư này đã được gửi đến <a style="color:#1b74e4;text-decoration:none" href="mailto:{{ $data->email }}" target="_blank">{{ $data->email }}</a>. <br>Để bảo vệ tài khoản của bạn, vui lòng không chuyển tiếp email này. <a style="color:#1b74e4;text-decoration:none" href="https://plats.network/privacy.html" target="_blank">Tìm hiểu thêm</a></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td height="10" style="line-height:10px">&nbsp;</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                           <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
                        </tr>
                     </tbody>
                  </table>
                  <span><img src="https://plats.network/img/logo-white.svg" style="border:0;width:1px;height:1px" class="CToWUd" data-bit="iit"></span>
               </td>
            </tr>
         </tbody>
      </table>
      <!--/100% body table-->
   </body>
</html>