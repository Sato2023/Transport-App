package com.example.transportapplication;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private EditText edtUsername, edtPassword;
    private String username, password;
    private String URL = "http://192.168.10.127/bachelor/transport-app/loginapp.php";
    private ProgressBar progressBar;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        username = "";
        password = "";

        edtUsername = findViewById(R.id.edtUsername);
        edtPassword = findViewById(R.id.edtPassword);
        progressBar = findViewById(R.id.progressbars);
        progressBar.setVisibility(View.GONE);
    }


    public void login(View view) {
        username = edtUsername.getText().toString().trim();
        password = edtPassword.getText().toString().trim();
        System.out.println("Testing 1");
        if(!username.equals("") && !password.equals("")){
            StringRequest stringRequest = new StringRequest(Request.Method.POST, URL, new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    System.out.println("Testing 2");
                    System.out.println(response);
                    if(response.equals("success")){
                        System.out.println("Testing 3");
                        Intent intent = new Intent(LoginActivity.this, NavigationActivity.class);
                        intent.putExtra("username",username);
                        startActivity(intent);
                        finish();
                    }
                    else if (response.equals("fail")){
                        System.out.println("Testing 3.1");
                        Toast.makeText(LoginActivity.this,"Mislykket pålogging", Toast.LENGTH_SHORT).show();
                    }
                }
            }, new Response.ErrorListener(){
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("Testing 3.2");

                    Toast.makeText(LoginActivity.this, error.toString().trim(), Toast.LENGTH_SHORT).show();

                }
            }){
                @Override
                protected Map<String, String> getParams() throws AuthFailureError {
                    Map<String, String> data = new HashMap<>();
                    data.put("username", username);
                    data.put("password", password);
                    return data;
                }
            };
            System.out.println("TEsting 4");
            RequestQueue requestQueue = Volley.newRequestQueue(getApplicationContext());
            requestQueue.add(stringRequest);
        }else{
            System.out.println("Testing 4");

            Toast.makeText(LoginActivity.this,"Skriv inn brukernavn og passord", Toast.LENGTH_SHORT).show();
        }
    }

    public void register(View view) {
        startActivity(new Intent(this, RegisterActivity.class));
    }
}