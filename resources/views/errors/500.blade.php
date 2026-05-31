<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 500 | Stepora</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            text-align: center;
        }
        .error-container {
            max-width: 600px;
            padding: 2rem;
        }
        h1 {
            font-size: 5rem;
            font-weight: 800;
            margin: 0;
            line-height: 1;
            letter-spacing: -0.05em;
        }
        h2 {
            font-size: 2rem;
            margin: 1rem 0;
            font-weight: 700;
        }
        p {
            color: #4b5563;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background: #111827;
            color: #ffffff;
            padding: 1rem 2rem;
            text-decoration: none;
            font-weight: 600;
            border-radius: 9999px;
            transition: opacity 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.9rem;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>500</h1>
        <h2>Oups ! Quelque chose s'est mal passé.</h2>
        <p>Nous rencontrons actuellement un problème technique. Pas d'inquiétude, nos équipes ont été notifiées et sont déjà sur le coup.</p>
        <a href="{{ url('/') }}" class="btn">Retourner à l'accueil</a>
    </div>
</body>
</html>
