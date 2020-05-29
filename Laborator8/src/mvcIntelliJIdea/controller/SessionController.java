package mvcIntelliJIdea.controller;

import mvcIntelliJIdea.model.User;

import javax.servlet.http.HttpSession;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.*;

class SessionController {
    private HttpSession session;

    SessionController(HttpSession session) {
        this.session = session;
    }

    void logToDatabase(User user) throws SQLException {

        String sqlStatement = "INSERT INTO sessions (code, creationTime, lastAccesed, username) VALUES (?,?,?,?)";

        try (Connection connection = DriverManager.getConnection("jdbc:postgresql://localhost:3306/wp", "teodoradan", "");
             PreparedStatement statement = connection.prepareStatement(sqlStatement)
        ) {
            statement.setString(1, session.getId());
            statement.setString(2, String.valueOf(session.getCreationTime()));
            statement.setString(3, String.valueOf(session.getLastAccessedTime()));
            statement.setString(4, user.getUsername());
            statement.executeUpdate();

        } catch (SQLException e) {
            throw new SQLException(e.getMessage());
        }
    }
}
