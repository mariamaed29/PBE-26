@php
    $ocorrencia = $notificacao->ocorrencia;
    $aluno = $ocorrencia?->aluno;
    $tipoLabel = $ocorrencia?->tipo === 'entrada_atrasada' ? 'Entrada atrasada' : 'Sa&iacute;da antecipada';
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notificacao->titulo }}</title>
</head>
<body style="margin:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f172a;padding:22px 26px;color:#ffffff;">
                            <p style="margin:0;font-size:12px;color:#93c5fd;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">SAFE</p>
                            <h1 style="margin:8px 0 0;font-size:22px;line-height:1.3;">{{ $notificacao->titulo }}</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px;">
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#374151;">{{ $notificacao->mensagem }}</p>

                            @if($ocorrencia && $aluno)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:12px;background:#f9fafb;">
                                    <tr>
                                        <td style="padding:16px;">
                                            <p style="margin:0 0 4px;font-size:12px;color:#6b7280;">Aluno</p>
                                            <p style="margin:0;font-size:16px;font-weight:700;color:#111827;">{{ $aluno->nome }}</p>
                                            <p style="margin:6px 0 0;font-size:13px;color:#4b5563;">RM {{ $aluno->rm }} &bull; Turma {{ $aluno->turma }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0 16px 16px;">
                                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="padding-top:12px;border-top:1px solid #e5e7eb;">
                                                        <p style="margin:0;font-size:12px;color:#6b7280;">Tipo</p>
                                                        <p style="margin:4px 0 0;font-size:14px;font-weight:700;color:#111827;">{!! $tipoLabel !!}</p>
                                                    </td>
                                                    <td style="padding-top:12px;border-top:1px solid #e5e7eb;">
                                                        <p style="margin:0;font-size:12px;color:#6b7280;">Data e hora</p>
                                                        <p style="margin:4px 0 0;font-size:14px;font-weight:700;color:#111827;">{{ $ocorrencia->data_ocorrencia?->format('d/m/Y H:i') }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <div style="margin-top:18px;">
                                    <p style="margin:0 0 6px;font-size:12px;color:#6b7280;">Motivo</p>
                                    <p style="margin:0;font-size:14px;line-height:1.6;color:#374151;">{{ $ocorrencia->motivo }}</p>
                                </div>
                            @endif

                            <p style="margin:24px 0 0;font-size:12px;line-height:1.5;color:#6b7280;">
                                Esta mensagem foi enviada automaticamente ap&oacute;s uma ocorr&ecirc;ncia de entrada ou sa&iacute;da ser autorizada.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
