package mvcIntelliJIdea.model;

import java.sql.*;

public class DBManager {
    private Connection con;
    private Statement stmt;

    public DBManager() {
        connect();
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

    public boolean getPuzzle(String username) throws SQLException {
        int r = 0;

        try {
            r = stmt.executeUpdate("select puzzle from users where username='" + username + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return r > 0;
    }

    public int getEmpty(String username) {
        int r = 0;

        try {
            r = stmt.executeUpdate("select empty from users where username='" + username + "'");
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return r;
    }
}
