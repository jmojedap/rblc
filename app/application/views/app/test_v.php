<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        html, body {
            margin: 0;
            height: 100%
        }

        .layout {
            height: 100%;
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .mid-section{

        }

        .container{
            max-width: 1140px;
            margin: 0 auto;
            border: 1px solid red;
        }

        header{
            background-color: #FF6529;
            color: white;
            height: 60px;
        }

        footer{
            min-height: 150px;
            background-color: #232323;
            color: white;
            padding: 0.5em;
        }
    </style>
</head>
<body>
    <div class="layout">
        <header>A</header>
        <div class="mid-section">
            <div class="container">
                aquí va el contenido
            </div>
        </div>
        <footer>Footer</div>
    </div>
</body>
</html>