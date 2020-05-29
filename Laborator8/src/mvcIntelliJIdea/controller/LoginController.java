package mvcIntelliJIdea.controller;

import java.io.IOException;
import java.sql.SQLException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import mvcIntelliJIdea.model.Authenticator;
import mvcIntelliJIdea.model.User;


public class LoginController extends HttpServlet {

    public LoginController() {
        super();
    }

    protected void doPost(HttpServletRequest request,
                          HttpServletResponse response) throws ServletException, IOException {

        String username = request.getParameter("username");
        String password = request.getParameter("password");
        RequestDispatcher rd;

        Authenticator authenticator = new Authenticator();
        String result = authenticator.authenticate(username, password);

        if (result.equals("success")) {
                rd = request.getRequestDispatcher("/succes.jsp");
                try {
                    authenticator.updatePuzzle(username);
                    authenticator.updateEmpty(username);
                } catch (SQLException e) {
                    e.printStackTrace();
                }

                User user = new User(username, password, 0, authenticator.getEmptyCell(), authenticator.getIntArray());
                request.setAttribute("user", user);

                HttpSession session = request.getSession();
                session.setAttribute("user", user);

                SessionController sessionController = new SessionController(session);
                try {
                    sessionController.logToDatabase(user);
                } catch (SQLException e) {
                    e.printStackTrace();
                }

        } else {
            rd = request.getRequestDispatcher("/error.jsp");
        }
        rd.forward(request, response);
    }

}
