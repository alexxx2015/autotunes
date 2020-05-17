package de.autotunes.car;

import org.json.JSONArray;
import org.json.JSONException;

import de.autotunes.R;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;

public class cls_updateModel implements Runnable {
	private String data;
	private Context ctx;
	private Activity act;
	
	protected class cls_brandListener implements AdapterView.OnItemSelectedListener{

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
		
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
	
	public void run() {
		// TODO Auto-generated method stub
		try {
			JSONArray jsonArr = new JSONArray(data);
			ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
			for(int i = 0; i < jsonArr.length(); i++){					
					arAd.add(jsonArr.getJSONArray(i).getString(1));
			}
	        Spinner brands = (Spinner)this.act.findViewById(R.id.brand);
	        brands.setOnItemSelectedListener(new cls_brandListener());
			arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
			brands.setAdapter(arAd);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
