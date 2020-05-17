package de.autotunes;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Spinner;
import android.widget.TextView;


@SuppressLint({ "ParserError", "ParserError", "ParserError", "ParserError", "ParserError", "ParserError" })
public class autotunesActivity extends Activity {
	
	TextView mProgressView;
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        
        //StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        //StrictMode.setThreadPolicy(policy); 
        super.onCreate(savedInstanceState);
        
        this.setContentView(R.layout.main);     
        
        //this.addExpListView();
        
		initCarView carView = new initCarView();
		carView.setActivity(this);
		runOnUiThread(carView);

        Spinner brands = (Spinner)this.findViewById(R.id.brand);	
        brands.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
        	public void onItemSelected(AdapterView<?> adapterView, View view, int position, long rowId) { 
                // Your code here
        		long r = rowId;
        		r = r+1;	
            } 

            public void onNothingSelected(AdapterView<?> adapterView) {
                return;
            } 
		});
        
    }
}