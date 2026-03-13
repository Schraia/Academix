<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Render</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #ffffff;
            font-family: Georgia, 'Times New Roman', serif;
        }

        body.preview-mode {
            background: #e5e7eb;
        }

        .certificate-stage {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .certificate-stage.preview-mode {
            padding: 20px;
        }

        .certificate-page {
            width: 1123px;
            height: 794px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .certificate-page.preview-mode {
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.16);
        }
    </style>
</head>
<body class="{{ $previewMode ? 'preview-mode' : '' }}">
    <div class="certificate-stage {{ $previewMode ? 'preview-mode' : '' }}">
        <div class="certificate-page {{ $previewMode ? 'preview-mode' : '' }}">
            @include($templateView)
        </div>
    </div>
</body>
</html>
