<!DOCTYPE html>
<html>
<head>
    <title>Sandi Sementara Inventaris Kemenhan</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="background-color: #ffffff; padding: 20px; border-radius: 4px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Halo, {{ $accounts[0]['name'] }}</h2>
        <p>Anda telah mengajukan pemulihan password untuk akun Anda di Sistem Inventaris Kemenhan. Berikut adalah informasi login dan password sementara Anda:</p>
        
        <div style="border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Nama Akun:</strong> {{ $accounts[0]['name'] }}</p>
            <div style="background-color: #eef2ff; padding: 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold; color: #333;">
                Email Login: {{ $accounts[0]['email'] }}
            </div>
            <div style="background-color: #fffbeb; padding: 10px; border-radius: 4px; font-weight: bold; color: #b45309; text-align: center; font-size: 18px; letter-spacing: 2px;">
                Password Sementara: {{ $accounts[0]['password'] }}
            </div>
        </div>
        
        <p><strong>Penting:</strong> Setelah berhasil login menggunakan password sementara di atas, segera ubah password Anda di menu <strong>Profil Saya</strong>.</p>
        
        <p style="margin-top: 30px; font-size: 14px; color: #777;">Terima kasih,<br>Tim Inventaris Kemenhan Pusdatin</p>
    </div>
</body>
</html>
