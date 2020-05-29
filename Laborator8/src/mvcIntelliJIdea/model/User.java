package mvcIntelliJIdea.model;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Arrays;

public class User {

    private String username;
    private String password;
    private int moves;
    private int empty;
    private Integer[] puzzle;

    public User(String username, String password, int moves, int empty, Integer[] puzzle) {
        this.username = username;
        this.password = password;
        this.moves = moves;
        this.empty = empty;
        this.puzzle = puzzle;
    }

    public User(String username, String password) {
        this.username = username;
        this.password = password;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public int getMoves() {
        return moves;
    }

    public void setMoves(int moves) {
        this.moves = moves;
    }

    public int getEmpty() {
        return empty;
    }

    public void setEmpty(int empty) {
        this.empty = empty;
    }

    public Integer[] getPuzzle() {
        return puzzle;
    }

    public void setPuzzle(Integer[] puzzle) {
        this.puzzle = puzzle;
    }

    public void incrementMoves() {
        this.moves += 1;
    }

    @Override
    public String toString() {
        return "User{" +
                "username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", moves=" + moves +
                ", empty=" + empty +
                ", puzzle=" + Arrays.toString(puzzle) +
                '}';
    }
}
