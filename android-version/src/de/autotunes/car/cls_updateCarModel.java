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

public class cls_updateCarModel implements Runnable {
	private String data;
	private Context ctx;
	private Activity act;
	private initCarView icv;
	
	public void setICV(initCarView p_icv){
		this.icv = p_icv;
	}
	
	public void setData(String p_dat){
		this.data = p_dat;
	}
	public void setCtx(Context p_ctx){
		this.ctx = p_ctx;
	}
	public void setActivity(Activity p_act){
		this.act = p_act;
	}
	
	@Override
	public void run() {		
		// TODO Auto-generated method stub
		try {
			JSONArray jsonArr = new JSONArray(data);
			ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
			Map<Integer, JSONArray> cm = new HashMap<Integer, JSONArray>();
			
			for(int i = 0; i < jsonArr.length(); i++){					
					arAd.add(jsonArr.getJSONArray(i).getString(1));
					cm.put(i, jsonArr.getJSONArray(i));
			}
	        Spinner model = (Spinner)this.act.findViewById(R.id.model);
			arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
			model.setAdapter(arAd);
			
			icv.setCarModel(cm);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
