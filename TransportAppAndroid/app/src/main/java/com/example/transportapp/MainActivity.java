package com.example.transportapp;

import androidx.appcompat.app.AppCompatActivity;

import android.os.AsyncTask;
import android.os.Bundle;
import android.provider.ContactsContract;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class MainActivity extends AppCompatActivity {
    DatabaseConnection databaseconnection = new DatabaseConnection();
    private String databasenavn = "jdbc:mysql://192.168.137.1:3306/adamdb";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        MyTask.execute();


    }
    private class MyTask extends AsyncTask<Void, Void, Void>{

        @Override
        protected Void doInBackground(Void... arg0){
            try{
                Class.forName("com.mysql.jdbc.Driver");
                Connection forbindelse = DriverManager.getConnection(databasenavn, "root", "Fredrikerkonge1");
                System.out.println("Ja database funker");
            }
            catch (Exception e){
                e.printStackTrace();
            }
            return null;
        }
    }

//    public class DatabaseConnection {
//
//        //private String databasedriver = "com.mysql.jdbc.Driver";
//        private Connection forbindelse;
//
//        private Statement utsagn;
//
//        public void lagForbindelse() throws Exception {
//            try {
//                forbindelse = DriverManager.getConnection(databasenavn,"Case","Esac");
//                System.out.println("Koblet til database");//Bruk eget passord
//            } catch(Exception e) {
//                throw new Exception("Kan ikke oppnå kontakt med databasen");
//            }
//        }
//        public ResultSet lesbruker() throws Exception {
//            ResultSet resultat = null;
//            String sql = "SELECT * FROM bruker";
//            try{
//                utsagn = forbindelse.createStatement();
//                resultat = utsagn.executeQuery(sql);
//            }catch(Exception e){throw new Exception("Kan ikke åpne databasetabelL");}
//            return resultat;
//        }
//    }


}