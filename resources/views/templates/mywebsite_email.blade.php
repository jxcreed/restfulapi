<style>
    .flat-button {
        background:#5EA32B; 
        border:0;
        box-shadow:#CCCCCC 0 0 0 inset;
        color:white;
        cursor:pointer;
        font-size:20px;
        height:40px;
        padding:0;
        position:relative;
        text-align:center;
        text-shadow:rgba(0, 0, 0, 0.247059) 0 1px 2px;
        vertical-align:top;
        width:25%;
        margin-top: -15px;
    }
    .steps {
        font-size:15px;
        text-align:left;
        padding:10px;
        height:65px;
        width:25%;
    }
</style>

<center>
    <table border="0" cellspacing="0" cellpadding="0" style="font-family: Arial; border: 1px #ebebeb solid;" width="65%">
            <tr><td align="left">
                <br/>
            </td></tr>
            <tr>
                <td colspan="4">
                    <table cellspacing="0" cellpadding="0" style="margin-left: 50px;font-family: Arial;" border="0">
                        <tr><td><p style="font-family:Arial,verdana,san serif;">
                            <br/><span style='margin-left:20px;position:absolute;margin-top:-15px;color:#999; font-size:12px;'>Information - Add <a style='color:#999;text-decoration:none;' href='mailto:noreply@mywebsite.com'>noreply@mywebsite.com</a> to your address book to make sure notification will be in your inbox.</span></p></td></tr>
                        <tr style="font-size: 15px">
                            <td>
                                {{-- Email Content --}}
                                @yield('content')

                                <h3 style="font-weight:normal;line-height: 1.5">All the best,<br/>Admin</h3>
                            </td>
                        </tr>
                        <tr><td><br/></td></tr>
                        <tr><td><p style="color:#999;font-size:13px;">----- <i>This is an auto generated email. Do not reply. -----</i></p></td></tr>
                        <tr><td><br/></td></tr>
                    </table>
                </td>
            </tr>
    </table>
</center>