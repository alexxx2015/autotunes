package de.autotunes.car;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import de.autotunes.R;
import de.autotunes.initCarView;

import android.app.Activity;
import android.content.Context;
import android.net.http.AndroidHttpClient;
import android.os.AsyncTask;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;

public class cls_updateCarSearch implements Runnable {
	private String data;
	private Context ctx;
	private Activity act;
	
	public void setData(String p_dat){
		this.data = p_dat;
	}
	public void setCtx(Context p_ctx){
		this.ctx = p_ctx;
	}
	public void setActivity(Activity p_act){
		this.act = p_act;
	}
	
	public void run() {		
		JSONObject jsonObj, ads, ad;
		JSONArray carAds;
		try {
			jsonObj = new JSONObject(data);
			if(jsonObj.get("r").equals(true)){
				ads = jsonObj.getJSONObject("ads");
				carAds = ads.getJSONArray("carAds");
				for(int i = 0; i < carAds.length(); i++){
					ad = carAds.getJSONObject(i);
					System.out.print(ad.get("carBrandID")+" "+ad.get("carBrandName"));
				}
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
