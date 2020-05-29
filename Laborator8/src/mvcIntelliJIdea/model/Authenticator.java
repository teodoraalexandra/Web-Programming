package mvcIntelliJIdea.model;

import java.sql.*;
import java.util.*;


public class Authenticator {
    private Statement stmt;
    private Connection con;

    private int emptyCell = -1;
    private Integer[] intArray = { 1, 2, 3, 4, 5, 6, 7, 8, 9 };

    public Authenticator() {
        connect();
        shuffleImages();
    }

    private void connect() {
        try {
            con = DriverManager.getConnection("jdbc:postgresql://localhost:3306/wp", "teodoradan", "");
            stmt = con.createStatement();
        } catch(Exception ex) {
            System.out.println("Connection error: " + ex.getMessage());
            ex.printStackTrace();
        }
    }

    private void shuffleImages() {

        //Shuffle the list with image positions
        List<Integer> intList = Arrays.asList(intArray);

        //This is for a random game
        Collections.shuffle(intList);

        intList.toArray(intArray);

        //Pick a random number from 1-9 to be the position for empty image
        Random generator = new Random();
        int randomIndex = generator.nextInt(intArray.length);

        emptyCell = intArray[randomIndex];

        intArray[emptyCell - 1] = 0;

        //This is for a hardcoded game - win in one move :))
        //intArray = new Integer[]{1, 5, 3, 4, 0, 6, 7, 8, 9};
        //emptyCell = 5;
    }

    public String authenticate(String username, String password) {
        ResultSet rs;
        String result = "error";
        try {
            rs = stmt.executeQuery("select * from users where username='" + username + "' and password='" + password+"'");
            if (rs.next()) {
                result = "success";
            }
            rs.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return result;
    }

    public void updatePuzzle(String username, Integer[] puzzle) throws SQLException {
        Array converted = con.createArrayOf("text", puzzle);

        try {
            stmt.executeUpdate("update users set puzzle='" + converted + "' " +
                    " where username='" + username + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    //This is used only at login
    public void updatePuzzle(String username) throws SQLException {
        Array converted = con.createArrayOf("text", intArray);

        try {
            stmt.executeUpdate("update users set puzzle='" + converted + "' " +
                    " where username='" + username + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void updateEmpty(String user, int emptyCell) {
        try {
            stmt.executeUpdate("update users set empty=" + emptyCell + " " +
                    " where username='" + user + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    //This is used only at login
    public void updateEmpty(String user) {
        try {
            stmt.executeUpdate("update users set empty=" + emptyCell + " " +
                    " where username='" + user + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    //Update moves for the user
    public void updateMoves(String user, int mvs) {
        try {
            stmt.executeUpdate("update users set moves=" + mvs + " " +
                    " where username='" + user + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public int getEmptyCell() {
        return emptyCell;
    }

    public void setEmptyCell(int emptyCell) {
        this.emptyCell = emptyCell;
    }

    public Integer[] getIntArray() {
        return intArray;
    }

    public void setIntArray(Integer[] intArray) {
        this.intArray = intArray;
    }
}
