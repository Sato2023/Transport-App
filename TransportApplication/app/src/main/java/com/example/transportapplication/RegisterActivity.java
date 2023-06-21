package com.example.transportapplication;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
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

public class RegisterActivity extends AppCompatActivity {

    private EditText edtUsername, edtName, edtLastname, edtPassword, edtRePassword;
    private Button btnRegister, btnBack;
    private String URL = "http://192.168.10.127/bachelor/transport-app/registrerapp.php";
    private String username, name, lastname, password, rePassword, security;
    private ProgressBar progressbar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        edtUsername = findViewById(R.id.edtUsername);
        edtName = findViewById(R.id.edtName);
        edtLastname = findViewById(R.id.edtLastname);
        edtPassword = findViewById(R.id.edtPassword);
        edtRePassword = findViewById(R.id.edtRePassword);
        btnRegister = findViewById(R.id.btnRegister);
        progressbar = findViewById(R.id.progressbar);
        username = name = lastname = password = rePassword = "";

        progressbar.setVisibility(View.GONE);

    }

    public void Register(View view) {
        progressbar.setVisibility(View.VISIBLE);

        username = edtUsername.getText().toString().trim();
        name = edtName.getText().toString().trim();
        lastname = edtLastname.getText().toString().trim();
        password = edtPassword.getText().toString().trim();
        rePassword = edtRePassword.getText().toString().trim();
        security = "0";

        System.out.println(username + name + lastname + password + rePassword + security);

        if(!password.equals(rePassword)){
            Toast.makeText(this,"Passordet stemmer ikke", Toast.LENGTH_SHORT).show();
        }
        else if(!username.equals("") && !name.equals("") && !lastname.equals("") && !password.equals("")){
            StringRequest stringRequest = new StringRequest(Request.Method.POST, URL, new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    System.out.println("Testing 2");
                    System.out.println(response);
                    if(response.equals("success")){
                        progressbar.setVisibility(View.GONE);
                        System.out.println("Testing 3 reg");
                        Toast.makeText(RegisterActivity.this,"Bruker registrert", Toast.LENGTH_SHORT).show();
                        Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
                        startActivity(intent);
                        finish();
                    }
                    else if (response.equals("fail")){
                        System.out.println("Testing 3.1");
                        Toast.makeText(RegisterActivity.this,"Noe gikk galt", Toast.LENGTH_SHORT).show();
                    }
                }
            }, new Response.ErrorListener(){
                @Override
                public void onErrorResponse(VolleyError error) {
                    System.out.println("Testing 3.2");

                    Toast.makeText(RegisterActivity.this, error.toString().trim(), Toast.LENGTH_SHORT).show();

                }
            }){
                @Override
                protected Map<String, String> getParams() throws AuthFailureError {
                    Map<String, String> data = new HashMap<>();
                    data.put("username", username);
                    data.put("password", password);
                    data.put("name", name);
                    data.put("lastname", lastname);
                    data.put("security", security);
                    return data;
                }
            };
            RequestQueue requestQueue = Volley.newRequestQueue(getApplicationContext());
            requestQueue.add(stringRequest);
        }

    }

    public void back(View view) {
        startActivity(new Intent(this, LoginActivity.class));
    }
}