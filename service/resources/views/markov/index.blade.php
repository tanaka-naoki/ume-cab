<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        textarea {
            width: 800px;
            height: 200px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <form method="post" action="/">
            <div>
                <textarea name="input_text">{{ $text }}</textarea>
            </div>

            <div>通常</div>
            <div style="width: 800px; border: 1px solid #000000; margin-top: 20px; ">
                {{ $result[0] }}
            </div>

            <div>自作</div>
            <div style="width: 800px; border: 1px solid #000000; margin-top: 20px; ">
                {{ $result[1] }}
            </div>
            <button type="submit" value="markov">送信</button>
        </form>
    </div>
</div>
</body>
</html>