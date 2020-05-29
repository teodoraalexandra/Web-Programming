package mvcIntelliJIdea.controller;

import mvcIntelliJIdea.model.User;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Arrays;


public class WinnerController extends HttpServlet {
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        String action = request.getParameter("action");

        if ((action != null) && action.equals("getAll")) {

            PrintWriter out = new PrintWriter(response.getOutputStream());

            HttpSession session = request.getSession();

            User user = (User) session.getAttribute("user");

            //Moves should be printed anyway
            //For example:
            //You make 5 moves, but the game is not done yet...
            //You make 5 moves, and you complete the puzzle! Congrats :)

            Integer[] puzzle = user.getPuzzle();
            Integer[] withoutZero = new Integer[8];
            int j = 0;
            for (Integer integer : puzzle) {
                if (integer != 0) {
                    withoutZero[j] = integer;
                    j++;
                }
            }

            String negative = "You have made " + user.getMoves() + " moves, but the game is not done yet... Keep going!";
            String positive = "You have made " + user.getMoves() + " moves, and you have completed the puzzle! Congrats :)";

            if (arraySortedOrNot(withoutZero)) {
                out.println(positive);
                out.flush();
            } else {
                out.println(negative);
                out.flush();
            }
        }
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
    }

    private boolean arraySortedOrNot(Integer[] puzzle) {
        for (int i = 1; i < puzzle.length; i++)

            //Unsorted pair found
            if (puzzle[i - 1] > puzzle[i])
                return false;

        //No unsorted pair found
        return true;
    }
}

