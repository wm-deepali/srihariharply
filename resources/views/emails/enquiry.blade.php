<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Sri Hariharply Enquiry Form</title>
<!--[if mso]>
<style type="text/css">
    table { border-collapse: collapse; }
</style>
<![endif]-->
</head>
<body style="margin:0; padding:0; background:#f1f2f4;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f2f4; padding:30px 0; font-family: Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden;">

                <tr>
                    <td style="background:#2D5EAD; padding:24px 30px; text-align:center;">
                        <h1 style="margin:0; color:#ffffff; font-size:22px; font-weight:600;">Sri Hariharply Enquiry Form</h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px; width:160px;">User Name</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px;">Mobile Number</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['phn_no'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px;">Email ID</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['email'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px;">Product Category</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['category_name'] ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px;">Product Brand</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['brand_name'] ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#6d7175; font-size:13px;">Product Name</td>
                                <td style="padding:10px 0; border-bottom:1px solid #e3e5e8; color:#202223; font-size:14px; font-weight:600;">{{ $data['product'] ?: '—' }}</td>
                            </tr>
                        </table>

                        <div style="margin-top:20px;">
                            <div style="color:#6d7175; font-size:13px; margin-bottom:6px;">Description</div>
                            <div style="background:#f1f2f4; border-radius:6px; padding:14px; color:#202223; font-size:14px; line-height:1.5;">
                                {{ $data['msg'] ?: '—' }}
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:18px 30px; background:#fafafa; border-top:1px solid #e3e5e8;">
                        <p style="margin:0; color:#8c9196; font-size:12px; font-style:italic;">Please do not reply to this e-mail as this is a system generated notification.</p>
                        <p style="margin:6px 0 0; color:#8c9196; font-size:12px;">Best Regards</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>