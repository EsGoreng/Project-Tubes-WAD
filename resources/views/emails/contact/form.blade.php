<!DOCTYPE html>
<html lang="id">

<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" cellpadding="24" cellspacing="0"
                    style="background:#ffffff;border-radius:8px; margin: 24px;">
                    <tr>
                        <td>

                            <img src="{{ asset('images/logo_main.png') }}" alt="SiBersih" width="120"
                                style="display:block;margin:0 auto 24px;">

                            <h2 style="margin:0 0 16px;color:#111827;">
                                Pesan Baru dari Formulir Kontak
                            </h2>

                            <p style="color:#374151;">
                                Anda menerima pesan baru dari website <strong>{{ config('app.name') }}</strong>.
                            </p>

                            <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0;">

                            <p>
                                <strong>Dari:</strong> {{ $details['email'] }}<br>
                                <strong>Subjek:</strong> {{ $details['subject'] }}
                            </p>

                            <div style="margin-top:16px;padding:16px;background:#f3f4f6;border-radius:6px;">
                                {!! nl2br(e($details['message'])) !!}
                            </div>

                            <p style="margin-top:24px;">
                                Hormat kami,<br>
                                <strong>Tim {{ config('app.name') }}</strong>
                            </p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>
