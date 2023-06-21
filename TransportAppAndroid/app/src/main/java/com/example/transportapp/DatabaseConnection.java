package com.example.transportapp;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.*;


public class DatabaseConnection {
    private String databasenavn = "jdbc:mysql://:3306/kino";
    //private String databasedriver = "com.mysql.jdbc.Driver";
    private Connection forbindelse;
        public void lagforbindelse() throws Exception {
            try {
                forbindelse = DriverManager.getConnection(databasenavn,"root","");//Bruk eget passord
                System.out.println("åpnet");
            } catch(Exception e) {
                throw new Exception("Kan ikke åpne kontakt med databasen");
            }
        }
}
