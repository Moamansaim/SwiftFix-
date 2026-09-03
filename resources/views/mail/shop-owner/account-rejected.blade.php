<!doctype html>
<html lang="ar" dir="rtl" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title>نتيجة طلب التسجيل — SwiftFix</title>

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

<table role="presentation"
       border="0"
       cellpadding="0"
       cellspacing="0"
       width="100%"
       style="background-color: #e8edf4">

    <tr>
        <td align="center" style="padding: 32px 12px">

            <table role="presentation"
                   border="0"
                   cellpadding="0"
                   cellspacing="0"
                   width="600"
                   class="email-container"
                   style="width: 600px; max-width: 600px">

                <!-- Top brand bar -->
                <tr>
                    <td style="height: 6px; background-color: #13294d">
                        &nbsp;
                    </td>
                </tr>

                <!-- Card -->
                <tr>
                    <td style="background-color: #ffffff">

                        <table role="presentation"
                               border="0"
                               cellpadding="0"
                               cellspacing="0"
                               width="100%">

                            <!-- Logo -->
                            <tr>
                                <td align="center"
                                    style="padding: 32px 24px 20px 24px">

                                    <img
                                        src="{{ asset('SwiftFix-Logo-email.png') }}"
                                        alt="SwiftFix"
                                        width="160"
                                        style="
                                            display: block;
                                            width: 160px;
                                            max-width: 160px;
                                            border: 0;
                                        "
                                    />

                                </td>
                            </tr>

                            <!-- Orange accent -->
                            <tr>
                                <td style="height: 4px; background-color: #f47221">
                                    &nbsp;
                                </td>
                            </tr>

                            <!-- Title -->
                            <tr>
                                <td align="center"
                                    class="email-px"
                                    style="padding: 32px 40px 8px 40px">

                                    <h1 style="
                                        margin: 0;
                                        font-size: 22px;
                                        line-height: 32px;
                                        font-weight: 700;
                                        color: #13294d;
                                    ">
                                        نتيجة طلب التسجيل
                                    </h1>

                                </td>
                            </tr>

                            <!-- Body -->
                            <tr>
                                <td class="email-px"
                                    style="
                                        padding: 8px 40px 24px 40px;
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
                                        مرحباً {{ $firstName }} {{ $lastName }}،
                                    </p>

                                    <p style="
                                        margin: 0 0 12px 0;
                                        font-size: 15px;
                                        line-height: 26px;
                                        color: #475569;
                                    ">
                                        نشكرك على اهتمامك بالتسجيل في منصة SwiftFix.
                                    </p>

                                    <p style="
                                        margin: 0;
                                        font-size: 15px;
                                        line-height: 26px;
                                        color: #475569;
                                    ">
                                        بعد مراجعة طلب التسجيل والمعلومات المرسلة،
                                        نأسف لإبلاغك بأنه لم تتم الموافقة على طلبك
                                        في الوقت الحالي.
                                    </p>

                                </td>
                            </tr>

                            <!-- Rejection message -->
                            <tr>
                                <td class="email-px"
                                    style="padding: 0 40px 32px 40px">

                                    <table role="presentation"
                                           border="0"
                                           cellpadding="0"
                                           cellspacing="0"
                                           width="100%"
                                           style="
                                               background-color: #f7f9fc;
                                               border: 1px solid #dce3ee;
                                           ">

                                        <tr>
                                            <td style="
                                                padding: 22px;
                                                text-align: center;
                                                font-size: 14px;
                                                line-height: 26px;
                                                color: #475569;
                                            ">

                                                يمكنك التواصل مع إدارة SwiftFix
                                                للحصول على مزيد من المعلومات
                                                حول سبب رفض الطلب.

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

                        </table>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #13294d">

                        <table role="presentation"
                               border="0"
                               cellpadding="0"
                               cellspacing="0"
                               width="100%">

                            <tr>
                                <td align="center"
                                    class="email-px"
                                    style="padding: 24px 32px 10px 32px">

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
                                <td align="center"
                                    class="email-px"
                                    style="padding: 8px 32px 24px 32px">

                                    <p style="
                                        margin: 0;
                                        font-size: 12px;
                                        line-height: 20px;
                                        color: #9bb0c9;
                                    ">
                                        جميع الحقوق محفوظة &copy; {{ date('Y') }} SwiftFix
                                    </p>

                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

                <tr>
                    <td style="height: 6px; background-color: #f47221">
                        &nbsp;
                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>