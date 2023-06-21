package com.example.transportapplication;

import static android.content.pm.PackageManager.PERMISSION_GRANTED;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.Build;
import android.os.Bundle;
import android.os.SystemClock;
import android.view.View;
import android.widget.Button;
import android.widget.Chronometer;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnSuccessListener;

import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;

public class NavigationActivity extends AppCompatActivity {
    //Variabler
    TextView txtDate, txtName, txtPosition, txtPosition2;
    Button btnStart, btnStop, btnBack;

    private Chronometer chronometer;
    private boolean running;
    private String username;
    private String currentDate;
    private String latitude;
    private String longitude;
    private String URL = "http://192.168.10.127/bachelor/transport-app/registrerTime.php";
    private String URL2 = "http://192.168.10.127/bachelor/transport-app/registrerRuteapp.php";
    private final static int REQUEST_CODE = 100;
    private Timer timer;
    private TimerTask timerTask;
    FusedLocationProviderClient fusedLocationProviderClient;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_navigation);
        //Kobling med activity
        txtDate = findViewById(R.id.txtDate);
        txtName = findViewById(R.id.txtName);
        txtPosition = findViewById(R.id.txtPosition);
        txtPosition2 = findViewById(R.id.txtPosition2);
        chronometer = findViewById(R.id.chronometer);
        Calendar calendar = Calendar.getInstance();
        btnStop = findViewById(R.id.btnStop);
        btnBack = findViewById(R.id.btnBack);



        fusedLocationProviderClient = LocationServices.getFusedLocationProviderClient(this);

        //Vise brukernavn
        String username = getIntent().getStringExtra("username");
        txtName.setText(username);

        //Vise dato
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
        String currentDate = sdf.format(calendar.getTime());
        txtDate.setText(currentDate);

        //Knapper
        btnStop.setVisibility(View.GONE);


    }

    public void start(View view) {
        if(!running){
            chronometer.setBase(SystemClock.elapsedRealtime());
            chronometer.start();
            running = true;
            btnBack.setVisibility(View.GONE);
            btnStop.setVisibility(View.VISIBLE);
            getCurrentLocation();
            username = txtName.getText().toString().trim();
            currentDate = txtDate.getText().toString().trim();
            latitude = txtPosition.getText().toString().trim();
            longitude = txtPosition2.getText().toString().trim();

            if (timer == null) {
                timer = new Timer();
                timerTask = new TimerTask() {
                    @Override
                    public void run() {
                        System.out.println("Halla");


                        //Registrere rute
                        username = txtName.getText().toString().trim();
                        currentDate = txtDate.getText().toString().trim();
                        latitude = txtPosition.getText().toString().trim();
                        longitude = txtPosition2.getText().toString().trim();


                        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL2, new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                System.out.println("Testing 2 rute");
                                System.out.println(response);
                                if(response.equals("success")){
                                    System.out.println("Testing 3 rute");
                                    //finish();
                                } else if (response.equals("fail")) {
                                    System.out.println("Testing 3.1 rute");
                                }
                            }
                        }, new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                System.out.println("Testing 3.2 rute");

                                Toast.makeText(NavigationActivity.this, error.toString().trim(), Toast.LENGTH_SHORT).show();

                            }
                        }) {
                            @Override
                            protected Map<String, String> getParams() throws AuthFailureError {
                                Map<String, String> data = new HashMap<>();
                                data.put("currentDate", currentDate);
                                data.put("latitude", latitude);
                                data.put("longitude", longitude);
                                data.put("username", username);
                                return data;
                            }
                        };
                        System.out.println("Testing 4 rute");

                        RequestQueue requestQueue = Volley.newRequestQueue(getApplicationContext());
                        requestQueue.add(stringRequest);
                    }


                };
                timer.schedule(timerTask, 1000, 5000);
            }
        }

    }


    public void stop(View view) {
        btnStop.setVisibility(View.GONE);
        btnBack.setVisibility(View.VISIBLE);

        long elapsedMillis = SystemClock.elapsedRealtime() - chronometer.getBase();
        int hours = (int) (elapsedMillis / 3600000);
        int minutes = (int) (elapsedMillis - hours * 3600000) / 60000;
        int seconds = (int) (elapsedMillis - hours * 3600000 - minutes * 60000) / 1000;

        if (timer != null) {
            timer.cancel();
            timer = null;
            timerTask = null;
        }
        String elapsedTimeString = String.format("%02d:%02d:%02d", hours, minutes, seconds);
        System.out.println(elapsedTimeString);



        username = txtName.getText().toString().trim();
        currentDate = txtDate.getText().toString().trim();
        System.out.println(username);
        System.out.println(currentDate);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                System.out.println("Testing 2");
                System.out.println(response);
                if(response.equals("success")){
                    System.out.println("Testing 3");
                    Toast.makeText(NavigationActivity.this, "Arbeidstiden er registrert", Toast.LENGTH_LONG).show();
                    //finish();
                } else if (response.equals("fail")) {
                    System.out.println("Testing 3.1");
                    Toast.makeText(NavigationActivity.this, "Noe gikk galt", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                System.out.println("Testing 3.2");

                Toast.makeText(NavigationActivity.this, error.toString().trim(), Toast.LENGTH_SHORT).show();

            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> data = new HashMap<>();
                data.put("currentDate", currentDate);
                data.put("elapsedSeconds", elapsedTimeString);
                data.put("username", username);
                return data;
            }
        };
        System.out.println("Testing 4");

        RequestQueue requestQueue = Volley.newRequestQueue(getApplicationContext());
        requestQueue.add(stringRequest);

        chronometer.setBase(SystemClock.elapsedRealtime());
        chronometer.stop();
        running = false;

    }

    public void logout(View view) {
        Intent intent = new Intent(NavigationActivity.this, LoginActivity.class);
        startActivity(intent);

    }


    private void getCurrentLocation(){
        LocationRequest locationRequest = LocationRequest.create()
                .setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY)
                .setInterval(10000) // 10 seconds
                .setFastestInterval(5000); // 5 seconds
        if (ContextCompat.checkSelfPermission(this, android.Manifest.permission.ACCESS_COARSE_LOCATION) == PERMISSION_GRANTED){
            fusedLocationProviderClient.requestLocationUpdates(locationRequest, new LocationCallback() {
                @Override
                public void onLocationResult(LocationResult locationResult) {
                    Location location = locationResult.getLastLocation();
                    System.out.println("location");
                    System.out.println(location);
                    if (location != null){
                        Geocoder geocoder = new Geocoder(NavigationActivity.this, Locale.getDefault());
                        List<Address> addresses = null;
                        try {
                            addresses = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(),1);
                            txtPosition.setText("" +addresses.get(0).getLatitude());
                            txtPosition2.setText("" +addresses.get(0).getLongitude());
                            System.out.println(+addresses.get(0).getLatitude());
                            System.out.println("Hip");
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                    }
                }
            }, null);
        }else{
            askPermission();
        }

    }

    //Gammel kode
    private void getLastLocation() {

        if (ContextCompat.checkSelfPermission(this, android.Manifest.permission.ACCESS_COARSE_LOCATION) == PERMISSION_GRANTED){
            fusedLocationProviderClient.getLastLocation().addOnSuccessListener(new OnSuccessListener<Location>() {
                @Override
                public void onSuccess(Location location) {
                    System.out.println("location");
                    System.out.println(location);
                    if (location != null){
                        Geocoder geocoder = new Geocoder(NavigationActivity.this,Locale.getDefault());
                        List<Address> addresses = null;
                        try {
                            addresses = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(),1);
                            txtPosition.setText("" +addresses.get(0).getLatitude());
                            txtPosition2.setText("" +addresses.get(0).getLongitude());
                            System.out.println(+addresses.get(0).getLatitude());
                            System.out.println("Hip");
                        } catch (IOException e) {
                            e.printStackTrace();
                        }

                    }

                }
            });
        }else{
            askPermission();

        }
    }

    private void askPermission() {
        ActivityCompat.requestPermissions(NavigationActivity.this, new String[]
                {android.Manifest.permission.ACCESS_FINE_LOCATION}, REQUEST_CODE);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {

        if (requestCode==REQUEST_CODE){
            if(grantResults.length>0 && grantResults[0] == PackageManager.PERMISSION_GRANTED){
                getCurrentLocation();
            }
        }else{
            Toast.makeText(this,"Required Permission", Toast.LENGTH_SHORT).show();
        }


        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }


}