package de.autotunes;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;

import de.autotunes.RangeSeekBar.OnRangeSeekBarChangeListener;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.ArrayAdapter;
import android.widget.BaseExpandableListAdapter;
import android.widget.ExpandableListAdapter;
import android.widget.ExpandableListView;
import android.widget.SimpleExpandableListAdapter;
import android.widget.Spinner;
import android.widget.SeekBar;
import android.widget.TextView;
import android.net.http.*;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.*;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;
import org.json.JSONTokener;

@SuppressLint({ "ParserError", "ParserError", "ParserError", "ParserError", "ParserError", "ParserError" })
public class ori_autotunesActivity extends Activity {
	
	TextView mProgressView;
	
	ExpandableListView mExpListView; 
	ExpandableListAdapter mExpListAdp;
	
	/**
	  * strings for group elements
	  */
	    static final String arrGroupelements[] =
	    {
	   "India",
	   "Australia",
	   "England",
	   "South Africa"
	 };
	    
	    /**
	     * strings for child elements
	     */
	    static final String arrChildelements[][] =
	    {
	      { 
	     "Sachin Tendulkar",
	     "Raina",
	     "Dhoni",
	     "Yuvi"
	      },
	      {
	     "Ponting",
	     "Adam Gilchrist",
	     "Michael Clarke"
	      },
	      {
	     "Andrew Strauss",
	     "kevin Peterson",
	     "Nasser Hussain"
	      },
	      {
	     "Graeme Smith",
	     "AB de villiers",
	     "Jacques Kallis"
	      }
	       };
	
	private class downloadCarBrand extends AsyncTask<Activity, Void, Void> {
		
		protected Void doInBackground(Activity... params) {
			if(params.length > 0){
				
				Activity actActv = params[0];
				
		    	AndroidHttpClient ahc = AndroidHttpClient.newInstance("autotunes");
		    	HttpGet hg = new HttpGet("http://www.autotunes.de/index/getbrand");
		    	try {
		    		HttpResponse hr = ahc.execute(hg);
		    		BufferedReader rd = new BufferedReader(new InputStreamReader(hr.getEntity().getContent()));
					String line = "", line2 = "";
					while((line = rd.readLine()) != null){
						line2 += line;
					}
					/*
					JSONTokener jsonToken = new JSONTokener(line2);
					while(jsonToken.more()){
						JSONObject jsonObject = (JSONObject)jsonToken.nextValue();
						Log.e(autotunesActivity.class.toString(), jsonObject.toString());
					}*/
					JSONArray jsonArr = new JSONArray(line2);
					for(int i = 0; i < jsonArr.length(); i++){
						Log.i(autotunesActivity.class.toString(), jsonArr.getString(i));
					}
		    	 } catch (Exception e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
		    	 }
			}
	    	 return null;
	     }
	 }
	
	private class ExpListAdapter extends BaseExpandableListAdapter{
		private Context context;
		
		public ExpListAdapter(Context p_context){
			context = p_context;
		}

		public Object getChild(int arg0, int arg1) {
			// TODO Auto-generated method stub
			return null;
		}

		public long getChildId(int groupPosition, int childPosition) {
			// TODO Auto-generated method stub
			return 0;
		}

		public View getChildView(int groupPosition, int childPosition,
				boolean isLastChild, View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			if(convertView == null){
				LayoutInflater layInfl = (LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				convertView = layInfl.inflate(R.layout.child, null);
			}
			TextView tvName = (TextView)convertView.findViewById(R.id.tvChild);
			tvName.setText(arrChildelements[groupPosition][childPosition]);
			
			return convertView;
		}

		public int getChildrenCount(int groupPosition) {
			// TODO Auto-generated method stub
			return arrChildelements[groupPosition].length;
		}

		public Object getGroup(int groupPosition) {
			// TODO Auto-generated method stub
			return null;
		}

		public int getGroupCount() {
			// TODO Auto-generated method stub
			return arrGroupelements.length-1;
		}

		public long getGroupId(int groupPosition) {
			// TODO Auto-generated method stub
			return 0;
		}

		public View getGroupView(int groupPosition, boolean isExpanded,
				View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			if(convertView == null){
				LayoutInflater inflater = (LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				convertView = inflater.inflate(R.layout.group, null);
			}
			
			TextView tvGroup = (TextView)convertView.findViewById(R.id.tvGroup);
			tvGroup.setText(arrGroupelements[groupPosition]);
			
			return convertView;
		}

		public boolean hasStableIds() {
			// TODO Auto-generated method stub
			return false;
		}

		public boolean isChildSelectable(int groupPosition, int childPosition) {
			// TODO Auto-generated method stub
			return false;
		}
		
		
	}
	
    public void onCreate(Bundle savedInstanceState) {
        
        //StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        //StrictMode.setThreadPolicy(policy); 
        super.onCreate(savedInstanceState);
        
        this.setContentView(R.layout.main);
        
        this.mProgressView = (TextView)findViewById(R.id.progressView);
        this.addPriceRangeBar();
        
        this.addExpListView();
        
        new downloadCarBrand().execute(this);
        BaseExpandableListAdapter bela = new ExpListAdapter(this);
        //ExpandableListView expListView = (ExpandableListView)findViewById(R.id.expListViewe1);
       // expListView.setAdapter(bela); 
        /*
        Spinner brands = (Spinner)findViewById(R.id.brand);
        ArrayAdapter<CharSequence> brandsAdapter = ArrayAdapter.createFromResource(this, R.array.brands_array, android.R.layout.simple_spinner_item);
        brandsAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        brands.setAdapter(brandsAdapter);*/
        
    }
    
    private void addExpListView(){
    	//mExpListView = (ExpandableListView)findViewById(R.id.expListViewe1);
    	//mExpListAdp = mExpListView.getExpandableListAdapter();
    }
	
	private void addPriceRangeBar(){
		RangeSeekBar<Integer> seekBar = new RangeSeekBar<Integer>(20, 75, this.getApplicationContext());
		
		seekBar.setOnRangeSeekBarChangeListener(new OnRangeSeekBarChangeListener<Integer>() {
		    
		        public void onRangeSeekBarValuesChanged(RangeSeekBar<?> bar, Integer minValue, Integer maxValue) {
		                // handle changed range values
		                Log.i("TAG", "User selected new range values: MIN=" + minValue + ", MAX=" + maxValue);
		        }
		});

		// add RangeSeekBar to pre-defined layout
		View viewEZ = (View)findViewById(R.id.viewEZ);
		ViewGroup viewEZParent = (ViewGroup)viewEZ.getParent();
		int idxEZ = viewEZParent.indexOfChild(viewEZ);
		ViewGroup layout = (ViewGroup) findViewById(R.id.layout_main);
		layout.addView(seekBar,idxEZ);
	}
}