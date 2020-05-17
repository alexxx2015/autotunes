package de.autotunes;

import android.annotation.SuppressLint;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.http.AndroidHttpClient;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.util.Calendar;
import java.util.LinkedList;
import java.util.Locale;
import java.util.Map;

import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import de.autotunes.car.*;

@SuppressLint("ParserError")
public class initCarView implements Runnable{
	private Context ctx;
	private Activity act;
	
	private int brand[];
	private LinkedList<Integer> ezFList;
	private LinkedList<Integer> ezTList;
	
	private LinkedList<Integer> priceFList;
	private LinkedList<Integer> priceTList;
	
	private LinkedList<Integer> powerFList;
	private LinkedList<Integer> powerTList;
	
	private LinkedList<Integer> kmFList;
	private LinkedList<Integer> kmTList;

	private Map<Integer,JSONArray> carBrands;
	private Map<Integer,JSONArray> carModels;
	
	private initCarView myICV;
	
	public void setCtx(Context p_ctx){
		this.ctx = p_ctx;
	}
	public void setActivity(Activity p_act){
		this.act = p_act;
	}

	protected class iniCarBrand extends AsyncTask<Activity, Void, Void> {
		
		protected Void doInBackground(Activity... params) {
			if(params.length > 0){
				
				Activity actActv = params[0];

		    	AndroidHttpClient ahc = AndroidHttpClient.newInstance("autotunes");
		    	HttpGet hg = new HttpGet("http://www.autotunes.de/index/getcarbrand");
		    	try {
		    		HttpResponse hr = ahc.execute(hg);
		    		BufferedReader rd = new BufferedReader(new InputStreamReader(hr.getEntity().getContent()));
					String line = "", line2 = "";
				    
					while((line = rd.readLine()) != null){
						line2 += line;
					}
					cls_updateBrand myRun = new cls_updateBrand();	
					myRun.setICV(myICV);
					myRun.setActivity(act);
					myRun.setCtx(act);
					myRun.setData(line2);
					act.runOnUiThread(myRun);				
				}
	    	  catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
	    	 }
			}
			
	    	 return null;
	     }
	 }
	


	protected class searchAds extends AsyncTask<Intent, Void, Void> {
		
		protected Void doInBackground(Intent... params) {
			if(params.length > 0){
				
				Intent intent = params[0];
				
				String urlParam = "http://www.autotunes.de/car/ajasearch";
				Bundle extras = intent.getExtras();
				//add brand
				if(extras.containsKey(autotunes.BRAND)){
					urlParam += "/b/"+extras.getString(autotunes.BRAND);
				}
				//add model 
				if(extras.containsKey(autotunes.MODEL)){
					urlParam += "/m/"+extras.getString(autotunes.MODEL);
				}

		    	AndroidHttpClient ahc = AndroidHttpClient.newInstance("autotunes");		    	
		    	HttpGet hg = new HttpGet(urlParam);
		    	try {
		    		HttpResponse hr = ahc.execute(hg);
		    		BufferedReader rd = new BufferedReader(new InputStreamReader(hr.getEntity().getContent()));
					String line = "";
					StringBuilder line2 = new StringBuilder();
				    
					while((line = rd.readLine()) != null){
						line2.append(line);
					}
					
					System.out.println(line2.toString());

					JSONObject jsonObj = new JSONObject(line2.toString());
					if(jsonObj.get("r").equals(true)){					
						intent.putExtra(autotunes.CAR_SEARCH_RESULT, line2.toString());					
						act.startActivity(intent);
					}
				}
		    	catch (Exception e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
		    	}
			}
			
	    	 return null;
	     }
	 }
	
	public void setCarBrand(Map<Integer,JSONArray> p_carBrands){
		this.carBrands = p_carBrands;
	}
	
	public void setCarModel(Map<Integer,JSONArray> p_carModels){
		this.carModels = p_carModels;
	}
	
	public Map<Integer,JSONArray> getCarBrand(){
		return this.carBrands;
	}
	
	public Map<Integer,JSONArray> getCarModel(){
		return this.carModels;
	}

	public void run() {
		this.myICV = this;
        new iniCarBrand().execute(this.act);
        
		this.initEZ();
		this.initPower();		
		this.initKm();
		this.initPrice();
		this.initSearchBtn();
	}
	
	private void initEZ(){
		this.ezFList = new LinkedList<Integer>();
		this.ezTList = new LinkedList<Integer>();

		ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		Calendar datum = Calendar.getInstance(Locale.getDefault());
		arAd.add(this.act.getString(R.string.txt_from));
		for(int i = datum.get(Calendar.YEAR); i >= datum.get(Calendar.YEAR)-50 ; i--){					
				arAd.add(Integer.toString(i));
				this.ezFList.add(i);
		}		
        Spinner ezYF = (Spinner)this.act.findViewById(R.id.ezYF);		
		arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		ezYF.setAdapter(arAd);
		
		ArrayAdapter<String> arAd2 = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		arAd2.add(this.act.getString(R.string.txt_to));
		for(int i = datum.get(Calendar.YEAR); i >= datum.get(Calendar.YEAR)-50 ; i--){					
				arAd2.add(Integer.toString(i));
				//this.ezTList.add(i);
		}		
        Spinner ezYT = (Spinner)this.act.findViewById(R.id.ezYT);		
		arAd2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		ezYT.setAdapter(arAd2);		
	}
	
	@SuppressLint("ParserError")
	private void initPower(){
		this.powerFList = new LinkedList<Integer>();
		this.powerTList = new LinkedList<Integer>();
		// TODO Auto-generated method stub
		ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		arAd.add(this.act.getString(R.string.txt_from));
		String[] power = this.act.getResources().getStringArray(R.array.power);
		String powerStr;
		for(int i = 0; i < power.length; i++){
			powerStr = power[i] + this.act.getResources().getString(R.string.kw);
			powerStr += " ("+(Integer.parseInt(power[i]) * 90/66)+this.act.getResources().getString(R.string.ps)+")";
			arAd.add(powerStr);			
			this.powerFList.add(Integer.parseInt(power[i]));
		}				
        Spinner powerF = (Spinner)this.act.findViewById(R.id.powerF);		
		arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		powerF.setAdapter(arAd);
		
		ArrayAdapter<String> arAd2 = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		arAd2.add(this.act.getString(R.string.txt_to));
		for(int i = power.length-1; i >= 0; i--){		
			powerStr = power[i] + this.act.getResources().getString(R.string.kw);
			powerStr += " ("+(Integer.parseInt(power[i]) * 90/66)+this.act.getResources().getString(R.string.ps)+")";
			arAd2.add(powerStr);
			
			this.powerTList.add(Integer.parseInt(power[i]));
		}		
        Spinner powerT = (Spinner)this.act.findViewById(R.id.powerT);		
		arAd2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		powerT.setAdapter(arAd2);
	}
	
	private void initKm(){
		this.kmFList = new LinkedList<Integer>();
		this.kmTList = new LinkedList<Integer>();
		ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		DecimalFormat df =  (DecimalFormat) DecimalFormat.getInstance();
		DecimalFormatSymbols dfs = df.getDecimalFormatSymbols();
		dfs.setGroupingSeparator('.');
		df.setDecimalFormatSymbols(dfs);
		arAd.add(this.act.getString(R.string.txt_from));
		String[] km = this.act.getResources().getStringArray(R.array.km);
		for(int i = 0; i < km.length; i++){
			arAd.add(df.format(Integer.parseInt(km[i]))+" "+this.act.getResources().getString(R.string.km));
			this.kmFList.add(Integer.parseInt(km[i]));
			
		}				
        Spinner kmF = (Spinner)this.act.findViewById(R.id.kmF);		
		arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		kmF.setAdapter(arAd);
		
		ArrayAdapter<String> arAd2 = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		arAd2.add(this.act.getString(R.string.txt_to));
		for(int i = km.length-1; i >= 0; i--){		
			arAd2.add(df.format(Integer.parseInt(km[i]))+" "+this.act.getResources().getString(R.string.km));
			this.kmTList.add(Integer.parseInt(km[i]));
		}		
        Spinner kmT = (Spinner)this.act.findViewById(R.id.kmT);		
		arAd2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		kmT.setAdapter(arAd2);
	}
	
	private void initPrice(){
		this.priceFList = new LinkedList<Integer>();
		this.priceTList = new LinkedList<Integer>();
		
		ArrayAdapter<String> arAd = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		DecimalFormat df =  (DecimalFormat) DecimalFormat.getInstance();
		DecimalFormatSymbols dfs = df.getDecimalFormatSymbols();
		dfs.setGroupingSeparator('.');
		df.setDecimalFormatSymbols(dfs);
		arAd.add(this.act.getString(R.string.txt_from));
		String[] price = this.act.getResources().getStringArray(R.array.price);
		for(int i = 0; i < price.length; i++){
			arAd.add(df.format(Integer.parseInt(price[i]))+" "+this.act.getResources().getString(R.string.currency));
			this.priceFList.add(Integer.parseInt(price[i]));
		}				
        Spinner priceF = (Spinner)this.act.findViewById(R.id.priceF);		
		arAd.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		priceF.setAdapter(arAd);
		
		ArrayAdapter<String> arAd2 = new ArrayAdapter<String>(this.act, android.R.layout.simple_spinner_item);
		arAd2.add(this.act.getString(R.string.txt_to));
		for(int i = price.length-1; i >= 0; i--){		
			arAd2.add(df.format(Integer.parseInt(price[i]))+" "+this.act.getResources().getString(R.string.currency));
			//this.priceTList.add(Integer.parseInt(price[i]));
		}		
        Spinner priceT = (Spinner)this.act.findViewById(R.id.priceT);		
		arAd2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);        
		priceT.setAdapter(arAd2);
	}
	
	private void initSearchBtn(){
		Button searchBtn = (Button)this.act.findViewById(R.id.searchBtn);
		searchBtn.setOnClickListener(new OnClickListener(){
			public void onClick(View arg0){
				Intent nextIntent = new Intent(act, hitlist.class);
				
				Spinner brand = (Spinner)act.findViewById(R.id.brand);
				JSONArray brandJson = carBrands.get(brand.getSelectedItemPosition());
				String carBrandId = null;
				try {
					carBrandId = brandJson.getString(0);
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

				Spinner model = (Spinner)act.findViewById(R.id.model);
				JSONArray modelJson = carModels.get(model.getSelectedItemPosition());
				String carModelId = null;
				try {
					carModelId = modelJson.getString(0);
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

				Spinner ezYF = (Spinner)act.findViewById(R.id.ezYF);
				Spinner ezYT = (Spinner)act.findViewById(R.id.ezYT);

				Spinner priceF = (Spinner)act.findViewById(R.id.priceF);
				Spinner priceT = (Spinner)act.findViewById(R.id.priceT);

				Spinner powerF = (Spinner)act.findViewById(R.id.powerF);
				Spinner powerT = (Spinner)act.findViewById(R.id.powerT);

				Spinner kmF = (Spinner)act.findViewById(R.id.kmF);
				Spinner kmT = (Spinner)act.findViewById(R.id.kmT);

				nextIntent.putExtra(autotunes.BRAND, carBrandId);
				nextIntent.putExtra(autotunes.MODEL, carModelId);
				nextIntent.putExtra(autotunes.EZYF, Integer.toString(ezYF.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.EZYT, Integer.toString(ezYT.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.PRICEF, Integer.toString(priceF.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.PRICET, Integer.toString(priceT.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.POWERF, Integer.toString(powerF.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.POWERT, Integer.toString(powerT.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.KMF, Integer.toString(kmF.getSelectedItemPosition()));
				nextIntent.putExtra(autotunes.KMT, Integer.toString(kmT.getSelectedItemPosition()));
				

		        new searchAds().execute(nextIntent);
			}
		});
	}
	
	/*
	private void initPrice2(){
		RangeSeekBar<Integer> seekBar = new RangeSeekBar<Integer>(1000, 100000, act.getApplicationContext());
		
		seekBar.setOnRangeSeekBarChangeListener(new OnRangeSeekBarChangeListener<Integer>() {
		        @Override
		        public void onRangeSeekBarValuesChanged(RangeSeekBar<?> bar, Integer minValue, Integer maxValue) {
		        	RangeSeekBar<Integer> rsk = (RangeSeekBar<Integer>) bar;
		    		DecimalFormat df =  (DecimalFormat) DecimalFormat.getInstance();
		        	DecimalFormatSymbols dfs = df.getDecimalFormatSymbols();
		        	dfs.setGroupingSeparator('.');
		        	df.setDecimalFormatSymbols(dfs);
		        	
		        	//andle changed range values
		        	TextView tv_priceMin = (TextView)act.findViewById(R.id.priceMin);
		        	TextView tv_priceMax = (TextView)act.findViewById(R.id.priceMax);

		        	if(minValue <= rsk.getAbsoluteMinValue()){
		        		tv_priceMin.setText(act.getResources().getText(R.string.priceMin));
		        	}else{
		        		tv_priceMin.setText(df.format(minValue)+"€");
		        	}
		        	
		        	if(maxValue >= rsk.getAbsoluteMaxValue()){
		        		tv_priceMax.setText(act.getResources().getText(R.string.priceMax));
		        	}else{
		        		tv_priceMax.setText(df.format(maxValue)+"€");
		        	}
		       }
		});

		// add RangeSeekBar to pre-defined layout
		View viewEZ = (View)act.findViewById(R.id.viewEZ);
		ViewGroup viewEZParent = (ViewGroup)viewEZ.getParent();
		int idxEZ = viewEZParent.indexOfChild(viewEZ);
		ViewGroup layout = (ViewGroup)act.findViewById(R.id.layout_main);
		layout.addView(seekBar,idxEZ);
	}
	*/
}
