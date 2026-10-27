<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reto Técnico | API RESTful</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .container {
            width: 100%;
            max-width: 680px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            margin-bottom: 24px;
            padding: 8px 14px;
            border: 1px solid #bfdbfe;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h1 {
            margin-bottom: 18px;
            font-size: clamp(2rem, 5vw, 3.2rem);
            line-height: 1.2;
            letter-spacing: -1px;
        }

        h1 span {
            color: #2563eb;
        }

        .description {
            max-width: 540px;
            margin: 0 auto 30px;
            color: #64748b;
            font-size: 1.05rem;
            line-height: 1.8;
        }

        .author {
            margin-top: 3rem;
            margin-bottom: 32px;
            font-size: 1rem;
            font-weight: 600;
            color: #334155;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 26px;
            border-radius: 10px;
            background: #2563eb;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.18);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .button:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .footer {
            margin-top: 64px;
            color: #94a3b8;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <main class="container">
        <div class="badge">Reto técnico</div>

        <h1>
            API <span>RESTful</span>
        </h1>

        <p class="description">
            Desarrollo de una API RESTful para la gestión de productos
            y precios, como parte de la evaluación técnica de <strong>Begento Healthcare Solutions.</strong>
        </p>

        <p class="author">
            👨‍💻 Desarrollado por Deyker Gil - @deykerag
        </p>

        <a href="{{ url('/swagger') }}" class="button">
            Consultar documentación <span aria-hidden="true">→</span>
        </a>
    </main>
</body>

</html>
