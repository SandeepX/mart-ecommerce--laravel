<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email | Allpasal</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <style>
        body {
            display: flex;
            justify-content: center;
        }

        .template {
            width: 400px;
            margin-top: 60px;
        }

        .view-browser-link {
            text-align: end;
        }

        .image {
            margin-top: 16px;
            text-align: center;
        }

        .image img {
            width: 150px;
            height: 50px;
        }

        .a1 {
            margin-top: 24px;
        }

        .a2 {
            margin-top: 16px;
        }

        .dashboard-link {
            margin-top: 32px;
            text-align: center;
        }

        .dashboard-link a {
            text-decoration: none;
        }

        .dashboard-link a button {
            width: 180px;
            padding: 16px;
            border: none;
            outline: none;
            color: white;
            cursor: pointer;
            text-align: center;
            border-radius: 12px;
            background-color: #0000FF;
        }

        .a3 {
            margin-top: 32px;
        }

        .a4 {
            margin-top: 24px;
        }

        .a5 {
            margin-top: 28px;
        }

        .footer {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
            font-style: italic;
            margin-bottom: 54px;
        }

        .footer-content {
            margin-top: 24px;
        }

        .fab {
            font-size: 24px;
        }

        .fa-facebook {
            color: blue;
            margin-right: 12px;
        }

        .fa-instagram {
            color: red;
            margin-right: 12px;
        }

        .fa-linkedin {
            color: blue;
            margin-right: 12px;
        }

        .fa-twitter {
            color: blue;
        }
    </style>
</head>

<body>
<div class="template">

    <div class="image">
        <img src="{{asset('default/images/alplogo.png')}}" alt="company_logo">
    </div>
    <div class="a1">Password Updated </div>
    <div class="a2">
        Your password has been updated on our {{config('app.name')}}.
        Please find your new login password below and don't forget to change password as soon as you login.
    </div>

    <div class="a3">
        Login Credentials<br><br>
        Email: {{$loginEmail}}<br>
        Password: {{$loginPassword}}<br><br>
        <a href={{$loginLink}} _target=”blank”>Click here to login</a>

    </div>

    <div class="a5">
        Thanks! We're excited to have you on board, <br> The Alpasal Team
    </div>
    <div class="footer">

        <div class="footer-content">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="footer-content">
            Copyright © 2020 Allpasal LLC., All rights reserved. <br>
            New Baneshwor, Kathmandu, KTM 44600
        </div>

    </div>
</div>
</body>