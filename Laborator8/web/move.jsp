<%--
  Created by IntelliJ IDEA.
  User: teodoradan
  Date: 16/05/2020
  Time: 12:15
  To change this template use File | Settings | File Templates.
--%>

<%@ page import="java.util.Arrays, mvcIntelliJIdea.model.User, java.util.*" %>
<%@ page import="java.util.stream.Collectors" %>
<%@ page import="mvcIntelliJIdea.model.Authenticator" %>
<%@ page import="mvcIntelliJIdea.model.DBManager" %>

<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Puzzle page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        .wrapper {
            margin-top: 10px;
            margin-bottom: 100px;
        }

        #left_one {
            height: 450px;
            width: 450px;
            float: left;
            display: inline;
            margin-left: 100px;
        }

        #right_one {
            height: 450px;
            width: 450px;
            float: right;
            display: inline;
            margin-right: 100px;
        }

        #right_one img {
            height: 450px;
            width: 450px;
        }

        .button {
            max-width: 380px;
        }

        .button a {
            text-decoration: none;
        }

        #done {
            margin-top: 400px;
            margin-left: 200px;
            position: absolute;
            min-width: 250px;
        }

        #logout {
            margin-top: 400px;
            margin-left: 825px;
            position: absolute;
            min-width: 250px;
        }
    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>
<body>
<div>

    <div style="margin: 25px auto auto; text-align:center">
        <h4 style="font-size:70px; margin:auto; text-align:center; color:black; text-shadow:2px 2px cyan;">
            <%
                mvcIntelliJIdea.model.User user = (mvcIntelliJIdea.model.User) request.getAttribute("user");
                out.println("Welcome, " + user.getUsername() + "!");

            %>
        </h4>
    </div>

    <div class = "wrapper">
        <div id = "left_one">
            <table>
                <tr>
                    <td id="1">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[0]%>.jpg"  alt="one"/>
                    </td>

                    <td id="2">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[1]%>.jpg"  alt="one"/>
                    </td>
                    <td id="3">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[2]%>.jpg"  alt="one"/>
                    </td>
                </tr>

                <tr>
                    <td id="4">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[3]%>.jpg"  alt="one"/>
                    </td>
                    <td id="5">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[4]%>.jpg"  alt="one"/>
                    </td>
                    <td id="6">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[5]%>.jpg"  alt="one"/>
                    </td>
                </tr>

                <tr>
                    <td id="7">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[6]%>.jpg"  alt="one"/>
                    </td>
                    <td id="8">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[7]%>.jpg"  alt="one"/>
                    </td>
                    <td id="9">
                        <img src="${pageContext.request.contextPath}/images/puzz<%=user.getPuzzle()[8]%>.jpg"  alt="one"/>
                    </td>
                </tr>
            </table>
        </div>
        <div id = "right_one">
            <img src="${pageContext.request.contextPath}/images/puzz.jpg"  alt="rightPuzzle"/>
        </div>
    </div>

    <div class="button" id="done">
        <button class="btn btn-lg btn-primary btn-block">I'm done</button>
    </div>

    <div class="button" id="logout">
        <a href="LogoutController">
            <button class="btn btn-lg btn-primary btn-block">Logout</button>
        </a>
    </div>

    <%
        Integer[] puzzle = user.getPuzzle();
        int emptyPosition = 0;
        for (int i = 0; i < puzzle.length; i++) {
            if (puzzle[i] == 0)
                emptyPosition = i;
        }

        String converted = Arrays.toString(puzzle);
    %>

    <script>
        $(document).ready(function(){

            function checkWin(callbackFunction) {
                $.get(
                    "WinnerController",
                    { action: 'getAll' },
                    callbackFunction
                );
            }

            function checkIfPossible(cellId, emptyCell, callbackFunction) {
                $.get(
                    "CheckController",
                    { action: 'getAll', cellId: cellId, emptyCell: emptyCell },
                    callbackFunction
                );
            }

            function updateUserData(cellPosition, callbackFunction) {
                var json = JSON.stringify(<%=converted%>);
                $.get(
                    "GameController",
                    { action: 'update', puzzle: json, emptyPosition: <%=emptyPosition%>, cellPosition: cellPosition,
                        username: "<%=user.getUsername()%>", password: "<%=user.getPassword()%>"},
                    callbackFunction
                );
            }

            $("#done").click(function () {
                checkWin(function (response) {
                    alert(response);
                })
            });

            $("#1").click(function() {

                checkIfPossible(1,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(0, function (response) {
                                location.reload();
                            })

                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }


                    }
                )
            });

            $("#2").click(function() {

                checkIfPossible(2,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(1, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#3").click(function() {

                checkIfPossible(3,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(2, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#4").click(function() {

                checkIfPossible(4,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(3, function (response) {
                                location.reload();
                            })

                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#5").click(function() {

                checkIfPossible(5,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(4, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#6").click(function() {

                checkIfPossible(6,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {


                            updateUserData(5, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#7").click(function() {

                checkIfPossible(7,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(6, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#8").click(function() {

                checkIfPossible(8,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {


                            updateUserData(7, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

            $("#9").click(function() {

                checkIfPossible(9,
                    <%=user.getEmpty()%>,
                    function(response) {
                        if (response.trim() === "possible") {

                            updateUserData(8, function (response) {
                                location.reload();
                            })


                        } else if (response.trim() === "impossible") {
                            alert("You can not make that move!");
                        } else {
                            console.log("Debug case.");
                        }
                    }
                )
            });

        });

    </script>

</div>

</body>
</html>
