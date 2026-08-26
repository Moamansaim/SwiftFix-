<!doctype html>
<html lang="ar" dir="rtl" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta name="color-scheme" content="light" />
  <meta name="supported-color-schemes" content="light" />
  <title>كود التحقق — SwiftFix</title>

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

      .otp-code {
        font-size: 28px !important;
        letter-spacing: 6px !important;
      }
    }
  </style>
</head>

<body
  style="margin: 0; padding: 0; background-color: #e8edf4; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif">
  <!-- Inbox preview -->
  <div
    style="display: none; font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all">
    كود التحقق الخاص بك من SwiftFix صالح لمدة 5 دقائق فقط.</div>

  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #e8edf4">
    <tr>
      <td align="center" style="padding: 32px 12px">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="email-container"
          style="width: 600px; max-width: 600px">
          <!-- Top brand bar -->
          <tr>
            <td style="height: 6px; line-height: 6px; font-size: 0; background-color: #13294d">&nbsp;</td>
          </tr>

          <!-- Card -->
          <tr>
            <td style="background-color: #ffffff">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <!-- Logo -->
                <tr>
                  <td align="center" style="padding: 32px 24px 20px 24px; background-color: #ffffff">
                    <img src="{{ asset('SwiftFix-Logo-email.png') }}" alt="SwiftFix" width="160" height="100"
                      style="display: block; width: 160px; height: auto; max-width: 160px; border: 0" />
                  </td>
                </tr>

                <!-- Orange accent -->
                <tr>
                  <td style="height: 4px; line-height: 4px; font-size: 0; background-color: #f47221">&nbsp;</td>
                </tr>

                <!-- Title -->
                <tr>
                  <td align="center" class="email-px" style="padding: 32px 40px 8px 40px">
                    <h1
                      style="margin: 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 22px; line-height: 32px; font-weight: 700; color: #13294d">
                      كود التحقق</h1>
                  </td>
                </tr>

                <!-- Body -->
                <tr>
                  <td class="email-px" style="padding: 8px 40px 0 40px; color: #334155; text-align: right">
                    <p
                      style="margin: 0 0 18px 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 16px; line-height: 26px; font-weight: 700; color: #13294d">
                      مرحباً {{ $user->first_name . ' ' . $user->last_name }}،</p>
                    <p
                      style="margin: 0 0 8px 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 15px; line-height: 26px; color: #475569">
                      تلقّينا طلباً لإعادة تعيين كلمة المرور الخاصة بك. استخدم الكود التالي لإتمام العملية.
                    </p>
                  </td>
                </tr>

                <!-- OTP -->
                <tr>
                  <td class="email-px" style="padding: 24px 40px 8px 40px">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
                      style="background-color: #f7f9fc; border: 1px solid #dce3ee">
                      <tr>
                        <td align="center" style="padding: 22px 16px 8px 16px">
                          <p
                            style="margin: 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 12px; letter-spacing: 1px; color: #64748b; font-weight: 700">
                            كود التحقق الخاص بك</p>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="padding: 6px 16px 18px 16px">
                          <p class="otp-code" style="
                                margin: 0;
                                font-family: &quot;Courier New&quot;, Consolas, Monaco, monospace;
                                font-size: 36px;
                                line-height: 44px;
                                font-weight: 700;
                                letter-spacing: 10px;
                                color: #13294d;
                                direction: ltr;
                                unicode-bidi: bidi-override;
                              ">
                            {{ $code }}
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="height: 3px; line-height: 3px; font-size: 0; background-color: #f47221">&nbsp;</td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- Expiry -->
                <tr>
                  <td align="center" class="email-px" style="padding: 16px 40px 8px 40px">
                    <p
                      style="margin: 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 14px; line-height: 22px; color: #f47221; font-weight: 700">
                      هذا الكود صالح لمدة 5 دقائق فقط</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #13294d">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" class="email-px" style="padding: 24px 32px 10px 32px">
                    <p
                      style="margin: 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 14px; line-height: 22px; font-weight: 700; color: #ffffff">
                      SwiftFix</p>
                    <p
                      style="margin: 6px 0 0 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 12px; line-height: 20px; color: #f47221">
                      صيانة سريعة · موثوقة · احترافية</p>
                  </td>
                </tr>
                <tr>
                  <td align="center" class="email-px" style="padding: 8px 32px 24px 32px">
                    <p
                      style="margin: 0; font-family: Tahoma, &quot;Segoe UI&quot;, Arial, sans-serif; font-size: 12px; line-height: 20px; color: #9bb0c9">
                      جميع الحقوق محفوظة &copy; {{ date('Y') }} SwiftFix</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height: 6px; line-height: 6px; font-size: 0; background-color: #f47221">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>