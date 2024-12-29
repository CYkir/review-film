<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Email Register</title>
  </head>
  <body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f9f9f9;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
      <h1 style="font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #333;">Selamat, {{ $name }}!</h1>
      <p style="font-size: 16px; color: #555;">Anda berhasil generate Ulang kode OTP. Berikut adalah kode OTP Anda:</p>
      <div style="text-align: center; margin: 20px 0;">
        <h3 style="display: inline-block; padding: 10px 20px; background-color: #e0f7fa; color: #00796b; font-size: 24px; font-weight: bold; border-radius: 5px; letter-spacing: 5px;">
          {{ $otp }}
        </h3>
      </div>
      <p style="font-size: 16px; color: #555;">Kode ini hanya berlaku selama 5 menit. Harap segera gunakan sebelum kedaluwarsa.</p>
      <p style="font-size: 14px; color: #999;">Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.</p>
    </div>
  </body>
</html>
