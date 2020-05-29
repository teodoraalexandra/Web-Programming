package mvcIntelliJIdea.controller;


import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;


public class CheckController extends HttpServlet {
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        String action = request.getParameter("action");

        if ((action != null) && action.equals("getAll")) {
            int cellId = Integer.parseInt(request.getParameter("cellId"));
            int emptyCell = Integer.parseInt(request.getParameter("emptyCell"));

            boolean result = true;
            PrintWriter out = new PrintWriter(response.getOutputStream());

            if (cellId == emptyCell)
                result = false;

            int rest = cellId % 3;
            int topPos = (cellId > 3) ? cellId - 3 : -1;
            int bottomPos = (cellId < 7) ? cellId + 3 : -1;
            int leftPos = (rest != 1) ? cellId - 1 : -1;
            int rightPos = (rest > 0) ? cellId +1 : -1;

            if (emptyCell != topPos && emptyCell != bottomPos && emptyCell != leftPos && emptyCell != rightPos)
                result = false;

            if (result) {
                out.println("possible");
            } else {
                out.println("impossible");
            }
            out.flush();
        }
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
    }
}

