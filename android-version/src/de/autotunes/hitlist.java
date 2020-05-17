package de.autotunes;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import de.autotunes.car.cls_updateBrand;
import de.autotunes.car.cls_updateCarSearch;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;

public class hitlist extends Activity {
	
	private Activity my;

	@Override
    public void onCreate(Bundle savedInstanceState) {
    	super.onCreate(savedInstanceState);
    	
    	this.setContentView(R.layout.hitlist);
    	
    	Bundle extras = this.getIntent().getExtras();
    	if(extras != null){
    	}
    	this.my = this;

		
		String carSearchResult = extras.getString(autotunes.CAR_SEARCH_RESULT);
		
		cls_updateCarSearch myRun = new cls_updateCarSearch();	
		myRun.setActivity(this);
		myRun.setCtx(this);
		myRun.setData(carSearchResult);
		this.runOnUiThread(myRun);	

		Button backBtn = (Button)findViewById(R.id.btn_back);
		backBtn.setOnClickListener(new OnClickListener(){
			public void onClick(View arg0){
				Intent nextIntent = new Intent(my, autotunesActivity.class);
				my.startActivity(nextIntent);
			}
		});
    }
}

/*
 * 
    		String brandId = extras.getString(autotunes.BRAND);
    		String modelId = extras.getString(autotunes.MODEL);
    		
    		
    		String priceFId = extras.getString(autotunes.PRICEF);
    		String priceTId = extras.getString(autotunes.PRICET);
    		
    		String ezIdF = extras.getString(autotunes.EZYF);
    		String ezTId = extras.getString(autotunes.EZYT);

    		String powerFId = extras.getString(autotunes.POWERF);
    		String powerTId = extras.getString(autotunes.POWERT);

    		String kmFId = extras.getString(autotunes.KMF);
    		String kmTId = extras.getString(autotunes.KMT);
    		

    		String[] kmStr = this.getResources().getStringArray(R.array.km);
    		String[] powerStr = this.getResources().getStringArray(R.array.power);
    		String[] priceStr = this.getResources().getStringArray(R.array.price);
    		
    		String kmF="", kmT="", powerF="", powerT="", priceF="", priceT="";
    		
    		if(kmStr[Integer.parseInt(kmFId)] != null){
    			kmF = kmStr[Integer.parseInt(kmFId)];
    		}
    		
    		if(kmStr[Integer.parseInt(kmTId)] != null){
    			kmT = kmStr[Integer.parseInt(kmTId)];
    		}
    		
    		if(powerStr[Integer.parseInt(powerFId)] != null){
    			powerF = powerStr[Integer.parseInt(powerFId)];
    		}
    		
    		if(powerStr[Integer.parseInt(powerTId)] != null){
    			powerT = powerStr[Integer.parseInt(powerTId)];
    		}
    		
    		if(priceStr[Integer.parseInt(priceFId)] != null){
    			priceF = priceStr[Integer.parseInt(priceFId)];
    		}
    		
    		if(priceStr[Integer.parseInt(priceTId)] != null){
    			priceT = priceStr[Integer.parseInt(priceTId)];
    		}
    		
    		TextView tv = (TextView)findViewById(R.id.hitlistView1);
    		//tv.setText(Integer.parseInt(ezF)+", "+ezT);*/
