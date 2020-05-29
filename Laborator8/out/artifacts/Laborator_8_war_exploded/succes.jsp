
<%@ page import="java.util.Arrays, mvcIntelliJIdea.model.User, java.util.*" %>

<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>User first page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        #wrapper {
            margin-top: 50px;
            text-align: center;
        }

        .button {
            margin-left: auto;
            margin-right: auto;
            max-width: 380px;
        }

        .button a {
            text-decoration: none;
        }

        #play {
            margin-top: 100px;
        }

        #logout {
            margin-top: 25px;
        }
    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>
<body>
<div id="wrapper">

<div>
    <h4 style="font-size:70px; margin:auto; text-align:center; color:black; text-shadow:2px 2px cyan;">
        <%
            mvcIntelliJIdea.model.User user = (mvcIntelliJIdea.model.User) request.getAttribute("user");
            out.println("Welcome, " + user.getUsername() + "!");
        %>
    </h4>
</div>

<div class="button" id="play">
    <a href="GameController">
        <button class="btn btn-lg btn-primary btn-block">Play</button>
    </a>
</div>


<div class="button" id="logout">
    <a href="LogoutController">
        <button class="btn btn-lg btn-primary btn-block">Logout</button>
    </a>
</div>

</div>

</body>
</html>
