<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<html>
<head>
    <title>Logout page</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Chango" rel="stylesheet">

    <style>
        body {
            padding: 0;
            margin: 0;
        }

        #success {
            position: relative;
            height: 100vh;
        }

        #success .success {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .success {
            max-width: 520px;
            width: 100%;
            line-height: 1.4;
        }

        .success>div:first-child {
            padding-left: 200px;
            padding-top: 12px;
            height: 170px;
            margin-bottom: 20px;
        }

        .success .icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 170px;
            height: 170px;
            background: #97FF70;
            border-radius: 7px;
            -webkit-box-shadow: 0px 0px 0px 10px #97FF70 inset, 0px 0px 0px 20px #fff inset;
            box-shadow: 0px 0px 0px 10px #97FF70 inset, 0px 0px 0px 20px #fff inset;
        }

        .success .icon h1 {
            font-family: 'Chango', cursive;
            color: #fff;
            font-size: 118px;
            margin: 0px;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display: inline-block;
            height: 60px;
            line-height: 60px;
        }

        .success h2 {
            font-family: 'Chango', cursive;
            font-size: 68px;
            color: #222;
            font-weight: 400;
            text-transform: uppercase;
            margin: 0px;
            line-height: 1.1;
        }

        .success p {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #222;
            margin-top: 5px;
        }

        .success a {
            font-family: 'Montserrat', sans-serif;
            color: #97FF70;
            font-weight: 400;
            text-decoration: none;
        }
    </style>
</head>
<body>


<div id="success">
    <div class="success">
        <div>
            <div class="icon">
                <h1>O</h1>
            </div>
            <h2>Successful<br>logout</h2>
        </div>
        <p>
            <%
            mvcIntelliJIdea.model.User user = (mvcIntelliJIdea.model.User) request.getAttribute("user");
            out.println("You are successfully logged out, " + user.getUsername() + ". Hope to see you soon!");

            %> <a href="login.html">Back to login</a></p>
    </div>
</div>
</body>
</html>
