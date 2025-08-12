<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Restablecer Contraseña</title>
    <!-- Tipografía profesional -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f7fa;        /* fondo exterior suave */
            --panel: #ffffff;     /* tarjeta blanca */
            --panel-2: #ffffff;   /* cabecera blanca */
            --border: #e5e7eb;    /* borde sutil */
            --text: #0b1220;      /* texto principal negro */
            --muted: #4b5563;     /* texto secundario gris */
            --accent: #000000ff;    /* azul corporativo sobrio */
            --danger: #b91c1c;    /* aviso/expira */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 24px;
        }

        .email-container {
            max-width: 640px;
            margin: 24px auto;
            background: var(--panel);
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .header {
            background: linear-gradient(180deg, var(--panel-2), var(--panel));
            padding: 28px;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }

        .brand {
            font-weight: 600;
            font-size: 18px;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--text);
        }

        .subtitle {
            margin-top: 6px;
            font-size: 13px;
            color: var(--muted);
            letter-spacing: .02em;
        }

        .content { padding: 28px; }

        h1 {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: .01em;
            margin-bottom: 14px;
            color: var(--text);
        }

        p { margin-bottom: 16px; color: var(--muted); }

        .btn-wrap { text-align: center; margin: 28px 0; }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            
            background: var(--accent);
            color: #ffffff !important;
            font-weight: 600;
            letter-spacing: .02em;
            border: 1px solid rgba(0, 0, 0, .06);
            box-shadow: 0 6px 16px rgba(29, 78, 216, .25);
        }
        .btn:hover {
            background: #242424ff;
        }

        .link-box { margin: 18px 0; text-align: center; }

        .code {
            display: inline-block;
            max-width: 100%;
            word-break: break-all;
            padding: 10px 14px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid var(--border);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 14px;
            color: var(--text);
        }

        .expire { color: var(--danger); font-size: 12px; }

        .support { margin-top: 18px; font-size: 14px; }

        .support a {
            color: var(--text);
            text-decoration: underline;
            font-weight: 600;
        }

        .disclaimer {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px dashed var(--border);
            font-size: 12px;
            color: #6b7280;
        }

        .footer {
            padding: 18px;
            text-align: center;
            background: #fafafa;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: #6b7280;
        }

        @media (max-width:640px) {
            body { padding: 12px; }
            .email-container { border-radius: 12px; }
            .content { padding: 22px; }
            .btn { width: 100%; }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="brand">Despacho Contable Baltazar Montes</div>
            <div class="subtitle">Restablecimiento de contraseña</div>
        </div>

        <div class="content">
            <h1>Hola,</h1>
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón de abajo para crear una nueva contraseña.</p>

            <div class="btn-wrap">
                <a class="btn" href="{{ $url }}">Restablecer contraseña</a>
            </div>

            <div class="link-box">
                <p>O copia y pega este enlace en tu navegador:</p>
                <div class="code">{{ $url }}</div>
            </div>

            <p class="expire">Este enlace expirará en 24 horas por motivos de seguridad.</p>
            <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje. Tu contraseña no cambiará.</p>

            <div class="support">
                <p>Si tienes problemas con el botón, copia y pega la URL en tu navegador.</p>
                <p>¿Necesitas ayuda? <a href="mailto:despachobm28@gmail.com">Contáctanos</a></p>
            </div>

            <div class="disclaimer">
                <p>Este correo fue enviado automáticamente. Por favor no respondas directamente a este mensaje.</p>
            </div>
        </div>

        <div class="footer">
            <p>© 2025 Despacho contable BM. Todos los derechos reservados.</p>
            <p>Av. Mariano Abasolo No. 37, Colonia Centro, Altotonga, Veracruz, México, 93700</p>
        </div>
    </div>
</body>
</html>
