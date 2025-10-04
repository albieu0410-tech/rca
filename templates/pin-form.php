<?php
/** @var array $_CONFIG */
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator PIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 mb-3 text-center">Administrator access</h1>
                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="pin" class="form-label">Site PIN</label>
                                <input type="password" name="pin" id="pin" class="form-control" required autofocus>
                                <div class="invalid-feedback">Please enter the PIN provided by Asiguram.</div>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        (function () {
            const form = document.querySelector('form');
            if (!form) return;
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        })();
    </script>
</body>
</html>
