<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كود التحقق</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Header Section -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); padding: 40px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">أهلاً بك معنا</h1>
                        </td>
                    </tr>

                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 40px 30px; color: #334155; text-align: right;">
                            <p style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1e293b;">
                                مرحباً : {{ $user->first_name . ' ' . $user->last_name }}
                            </p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.6; color: #64748b;">
                                لقد تلقينا طلباً للتحقق من حسابك أو إعادة تعيين كلمة المرور الخاصة بك. يرجى استخدام كود التحقق أدناه لإتمام العملية. الكود صالح لمدة <strong>5 دقائق</strong> فقط.
                            </p>

                            <!-- Code Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 10px 0;">
                                        <div style="background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 8px; padding: 15px 30px; display: inline-block; letter-spacing: 6px; font-size: 32px; font-weight: 700; color: #4f46e5; direction: ltr;">
                                            {{ $code }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0 0; font-size: 14px; line-height: 1.6; color: #94a3b8;">
                                إذا لم تقم بهذا الطلب، يمكنك تجاهل هذا البريد الإلكتروني بأمان.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #94a3b8;">
                            <p style="margin: 0;">جميع الحقوق محفوظة &copy; {{ date('Y') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>