```blade
<!doctype html>
<html lang="ar" dir="rtl" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />

    <title>الرد على شكواك — SwiftFix</title>

    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
        }

        a {
            color: #f47221;
        }

        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
            }

            .email-px {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
    </style>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #e8edf4;
    font-family: Tahoma, 'Segoe UI', Arial, sans-serif;
">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background-color: #e8edf4;">
        <tr>
            <td align="center" style="padding: 32px 12px;">

                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    class="email-container" style="width: 600px; max-width: 600px;">

                    <!-- Top brand bar -->
                    <tr>
                        <td style="
                                height: 6px;
                                background-color: #13294d;
                            ">
                            &nbsp;
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td style="background-color: #ffffff;">

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

                                <!-- Logo -->
                                <tr>
                                    <td align="center" style="padding: 32px 24px 20px 24px;">
                                        <img src="{{ asset('SwiftFix-Logo-email.png') }}" alt="SwiftFix" width="160"
                                            style="
                                                display: block;
                                                width: 160px;
                                                max-width: 160px;
                                                border: 0;
                                            " />
                                    </td>
                                </tr>

                                <!-- Orange accent -->
                                <tr>
                                    <td style="
                                            height: 4px;
                                            background-color: #f47221;
                                        ">
                                        &nbsp;
                                    </td>
                                </tr>

                                <!-- Title -->
                                <tr>
                                    <td align="center" class="email-px" style="padding: 32px 40px 8px 40px;">
                                        <h1 style="
                                                margin: 0;
                                                font-size: 22px;
                                                line-height: 32px;
                                                font-weight: 700;
                                                color: #13294d;
                                            ">
                                            تم الرد على شكواك
                                        </h1>
                                    </td>
                                </tr>

                                <!-- Greeting -->
                                <tr>
                                    <td class="email-px" style="
                                            padding: 8px 40px 0 40px;
                                            color: #334155;
                                            text-align: right;
                                        ">

                                        <p style="
                                                margin: 0 0 18px 0;
                                                font-size: 16px;
                                                line-height: 26px;
                                                font-weight: 700;
                                                color: #13294d;
                                            ">
                                            مرحباً
                                            {{ $complaint->user->first_name }}
                                            {{ $complaint->user->last_name }}،
                                        </p>

                                        <p style="
                                                margin: 0;
                                                font-size: 15px;
                                                line-height: 26px;
                                                color: #475569;
                                            ">
                                            نود إبلاغك بأنه تمت مراجعة الشكوى
                                            التي قمت بتقديمها عبر منصة SwiftFix،
                                            وقد قامت الإدارة بالرد عليها.
                                        </p>

                                    </td>
                                </tr>

                                <!-- Complaint information -->
                                <tr>
                                    <td class="email-px" style="padding: 24px 40px 8px 40px;">

                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            width="100%" style="
                                                background-color: #f7f9fc;
                                                border: 1px solid #dce3ee;
                                            ">

                                            <!-- Shop -->
                                            <tr>
                                                <td style="
                                                        padding: 20px;
                                                        text-align: right;
                                                        font-size: 14px;
                                                        line-height: 26px;
                                                        color: #475569;
                                                    ">

                                                    <strong style="color: #13294d;">
                                                        الورشة:
                                                    </strong>

                                                    {{ $complaint->shop->shop_name }}

                                                    <br>


                                                    <strong style="color: #13294d;">
                                                        حالة الشكوى:
                                                    </strong>

                                                    <span style="
                                                            color: #16a34a;
                                                            font-weight: 700;
                                                        ">
                                                        تم الرد
                                                    </span>

                                                </td>
                                            </tr>

                                            <!-- Orange separator -->
                                            <tr>
                                                <td style="
                                                        height: 3px;
                                                        background-color: #f47221;
                                                    ">
                                                    &nbsp;
                                                </td>
                                            </tr>

                                        </table>

                                    </td>
                                </tr>

                                <!-- Complaint subject -->
                                <tr>
                                    <td class="email-px" style="padding: 16px 40px 8px 40px;">

                                        <h3 style="
                                                margin: 0 0 10px 0;
                                                font-size: 16px;
                                                line-height: 26px;
                                                color: #13294d;
                                            ">
                                            موضوع الشكوى
                                        </h3>

                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            width="100%" style="
                                                background-color: #ffffff;
                                                border: 1px solid #dce3ee;
                                            ">
                                            <tr>
                                                <td style="
                                                        padding: 16px;
                                                        text-align: right;
                                                        font-size: 14px;
                                                        line-height: 25px;
                                                        color: #475569;
                                                    ">
                                                    {{ $complaint->subject }}
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                             

                                <!-- Admin reply -->
                                <tr>
                                    <td class="email-px" style="padding: 16px 40px 8px 40px;">

                                        <h3 style="
                                                margin: 0 0 10px 0;
                                                font-size: 16px;
                                                line-height: 26px;
                                                color: #13294d;
                                            ">
                                            رد الإدارة
                                        </h3>

                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            width="100%" style="
                                                background-color: #fff7f0;
                                                border: 1px solid #f6c9a8;
                                            ">
                                            <tr>
                                                <td style="
                                                        padding: 18px;
                                                        text-align: right;
                                                        font-size: 15px;
                                                        line-height: 27px;
                                                        color: #475569;
                                                    ">
                                                    {!! $complaint->admin_reply !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="
                                                        height: 3px;
                                                        background-color: #f47221;
                                                    ">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>

                                <!-- Closing message -->
                                <tr>
                                    <td align="center" class="email-px" style="padding: 24px 40px 28px 40px;">

                                        <p style="
                                                margin: 0;
                                                font-size: 14px;
                                                line-height: 22px;
                                                color: #f47221;
                                                font-weight: 700;
                                            ">
                                            شكراً لتواصلك مع منصة SwiftFix.
                                        </p>

                                        <p style="
                                                margin: 8px 0 0 0;
                                                font-size: 13px;
                                                line-height: 22px;
                                                color: #64748b;
                                            ">
                                            نحن نحرص دائماً على تحسين تجربتك
                                            وتقديم أفضل خدمة ممكنة.
                                        </p>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #13294d;">

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">

                                <tr>
                                    <td align="center" class="email-px" style="padding: 24px 32px 10px 32px;">

                                        <p style="
                                                margin: 0;
                                                font-size: 14px;
                                                line-height: 22px;
                                                font-weight: 700;
                                                color: #ffffff;
                                            ">
                                            SwiftFix
                                        </p>

                                        <p style="
                                                margin: 6px 0 0 0;
                                                font-size: 12px;
                                                line-height: 20px;
                                                color: #f47221;
                                            ">
                                            صيانة سريعة · موثوقة · احترافية
                                        </p>

                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" class="email-px" style="padding: 8px 32px 24px 32px;">

                                        <p style="
                                                margin: 0;
                                                font-size: 12px;
                                                line-height: 20px;
                                                color: #9bb0c9;
                                            ">
                                            جميع الحقوق محفوظة
                                            &copy;
                                            {{ date('Y') }}
                                            SwiftFix
                                        </p>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- Bottom orange bar -->
                    <tr>
                        <td style="
                                height: 6px;
                                background-color: #f47221;
                            ">
                            &nbsp;
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
```