package mvcIntelliJIdea.controller;

import mvcIntelliJIdea.model.Authenticator;
import mvcIntelliJIdea.model.User;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.sql.SQLException;
import java.util.Arrays;
import java.util.Collections;

public class GameController extends HttpServlet {
    public GameController() {
        super();
    }

    protected void doGet(HttpServletRequest request,
                          HttpServletResponse response) throws ServletException, IOException {

        RequestDispatcher rd;
        rd = request.getRequestDispatcher("/move.jsp");
        String action = request.getParameter("action");

        HttpSession session = request.getSession();

        User user = (User) session.getAttribute("user");
        if (user == null || user.getUsername().equals("") || user.getPassword().equals(""))
            return;

        if ((action != null) && action.equals("update")) {

            //Here we will update the user data ...
            String puzzle = request.getParameter("puzzle");
            String emptyPosition = request.getParameter("emptyPosition");
            String cellPosition = request.getParameter("cellPosition");

            Integer[] prepareForConverting = new Integer[9];

            char[] arr = puzzle.toCharArray();
            int idx = 0;
            for (int i = 0; i < arr.length; i++) {
                if (i % 2 == 1) {
                    prepareForConverting[idx] = Character.getNumericValue(arr[i]);
                    idx += 1;
                }
            }

            Collections.swap(Arrays.asList(prepareForConverting), Integer.parseInt(emptyPosition), Integer.parseInt(cellPosition));

            int updateCellPosition = Integer.parseInt(cellPosition) + 1;

            String username = request.getParameter("username");
            String password = request.getParameter("password");

            //...in the model too
            user.setUsername(username);
            user.setPassword(password);
            user.incrementMoves();
            user.setPuzzle(prepareForConverting);
            user.setEmpty(updateCellPosition);

            //...and in the database too
            Authenticator authenticator = new Authenticator();
            try {
                authenticator.updatePuzzle(username, prepareForConverting);
                authenticator.updateEmpty(username, updateCellPosition);
                authenticator.updateMoves(username, user.getMoves());
            } catch (
                    SQLException e) {
                e.printStackTrace();
            }
        }

        request.setAttribute("user", user);

        rd.forward(request, response);
    }
}
