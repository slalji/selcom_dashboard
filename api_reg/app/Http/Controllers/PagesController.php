<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Response;
use League\Flysystem\Filesystem; 
use Auth;


class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function home(){
        
        
        $user = Auth::user();  
        
        $projects = DB::table('projects')
                    ->select(DB::raw('id, title, vendor, public, private'))
                    ->where( 'accountNo', '=', $user->accountNo)
                    ->get();
        $documents = $this->list_doc_menu(); 

        return view('home', compact('projects','docs'));
           
    }
    function documentation(){
        
        
        $user = Auth::user();  
        
        $docs = DB::table('documentation')
                    ->select(DB::raw('id, title, description'))
                    ->get();

        return view('documentation', compact('docs'));
           
    }
    function list_doc_menu(){        
         
        $docs = DB::table('documentation')
                    ->select(DB::raw('id,title'))
                    ->get();

        return view('documentation', compact('docs'));
           
    }
    function show_doc($id){
                
        $docs = DB::table('documentation')
                    ->select(DB::raw('*'))
                    ->where('id','=',$id)
                    ->get();

        return view('document', compact('docs'));
           
    }
     
    function create_keys(Request $request){
        $vendor = $this->rand_num(8);
        $title = $request->title;
        $rsaKey = openssl_pkey_new(array( 
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));
        $res = self::_privatePublicKeys();
       
         
        $accountNo = $request->accountNo;
        //$private = str_replace(' ','\r\n',$res['private']);
       // $public = str_replace(' ','\r\n',$res['public']);

        $private =  $res['private'];
        $public =  $res['public'];
        
         
        $id = DB::table('projects')->insertGetId(
         ['title'=>$title,'vendor'=>$vendor,'public'=>$public,'private'=>$private, 'accountNo'=>$accountNo]);
         
        //mysqli_query($conn,$sql) or die(mysqli_error($conn).' '.$sql.$data);
        //return view('home', compact('projects'));
        return redirect(route('keys',$id));
    }
    function _privatePublicKeys(){ 
       
         
        // generate 2048-bit RSA key
        $pkGenerate = openssl_pkey_new(array(
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ));
         
        // get the private key
        openssl_pkey_export($pkGenerate,$pkGeneratePrivate); // NOTE: second argument is passed by reference
         
        // get the public key
        $pkGenerateDetails = openssl_pkey_get_details($pkGenerate);
        $pkGeneratePublic = $pkGenerateDetails['key'];
         
        // free resources
        openssl_pkey_free($pkGenerate);
         
        // fetch/import public key from PEM formatted string
        // remember $pkGeneratePrivate now is PEM formatted...
        // this is an alternative method from the public retrieval in previous
        $pkImport = openssl_pkey_get_private($pkGeneratePrivate); // import
        $pkImportDetails = openssl_pkey_get_details($pkImport); // same as getting the public key in previous
        $pkImportPublic = $pkImportDetails['key'];
        openssl_pkey_free($pkImport); // clean up
         return $res = array('private'=>$pkGeneratePrivate, 'public'=>$pkGeneratePublic);
        // let's see 'em
        die( "\n".$pkGeneratePrivate
            ."\n".$pkGeneratePublic
            ."\n".$pkImportPublic
            ."\n".'Public keys are '.(strcmp($pkGeneratePublic,$pkImportPublic)?'different':'identical').'.');
         

    }
    function keys($project){
                
        $projects = DB::table('projects')
                    ->select(DB::raw('id, title, vendor, public, private'))
                    ->where( 'id', '=', $project)
                    ->get();
        $documents = $this->list_doc_menu(); 

        return view('keys', compact('projects','docs'));
           
    }
    function save_keys($project){
         /* copy vendor and public key to member table. delete private key*/        
        $project = DB::table('projects')
                    ->select(DB::raw('id, accountNo, title, vendor, public, private'))
                    ->where( 'id', '=', $project)
                    ->get()->first();
        //die(print_r( $project->public));
        /*$posts = DB::table('members')->insert(
            ['username' =>$project->title,'vendor' => $project->vendor, 'public' => $project->public ]
        );*/
                    
        return back();
        //return view('keys', compact('projects','docs'));
           
    }
    function dashboard(){
         
            $currentMonth = date('m');
            $currentMonth = '03';
            //$tiles = DB::table('transactions')->get();
            $tiles = DB::table('transactions')
                    ->select(DB::raw('utility_code, format(sum(amount),0) as amnt, count(amount) as cnt'))
                    ->where( 'type', '=', 'DEBIT')
                    ->whereMonth('fulltimestamp','=','03')
                    ->groupBy('utility_code')
                    ->orderBy('cnt','desc')->take(6)
                    ->get();         
        
            return view('dashboard', compact('tiles'));
    }

    function init_nbc(Request $request){
        $transactions = DB::table('transactions')
        ->join('members', 'members.id', '=', 'transactions.id')        
        ->select('transactions.id as tid', 'fulltimestamp', 'terminal', 'members.fullname', 'members.ip_address', 'utility_type', 'amount','utility_reference', 'msisdn', 'reference', 'transid', 'result', 'message' )
        ->orderBy('tid')->take(0)  
        ->get();

        //$where ='';
        //$whereOr='';  
        $sql='';  
        $totalData = 0; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
               
        
        if( !empty($request->columns[0]['search']['value']) ){ 
            
            
            $where = ' WHERE transactions.id = members.id' ;
            $sql = "SELECT transactions.id, fulltimestamp, terminal, members.fullname, members.ip_address, utility_type, amount,utility_reference, msisdn, reference, transid, result, message from transactions join members on members.id = transactions.id  ";
        
            //$where.=" AND (";
            $exp = explode('&',$request->columns[0]['search']['value']);
            
            $temp = explode(':',$exp[0]);
            
            if ($temp[0] == 'fulltimestamp' && $temp[1] !=''){
                
                $range = explode('|',$temp[1]);		
                $start = trim($range[0]); //name
                $end = trim($range[1]); //name
                $where.=" AND fulltimestamp >= '".$start."' AND fulltimestamp < ('" .$end. "' + INTERVAL 1 DAY) ";
         
            }
            array_splice($exp, 0, 1);
             
            foreach ($exp as $e){
                $arr = explode(':', $e);
                $key = trim($arr[0]);
                $val = trim($arr[1]);
                if ($key == 'transid' && $val !='')
                    $where.=" AND (".$key." like '%" . $val ."%' or reference like '%".$val."%') ";
                
                else if ($key == 'utility_reference' && $val !='')
                    $where.=" AND ".$key." like '%" . $val ."%'" ;
                else if ($key == 'result' && $val !='')
                    $where.=" AND ".$key." like '%" . $val ."%'" ;
                /*else if ($key == 'download' && $val !='')
                $download = $val;*/
            }
             
        
            $sql .= $where;
            $transactions = DB::select( DB::raw($sql) );

            $totalData = count($transactions); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
            
            $sql.= " ORDER BY fulltimestamp   ".$request->order[0]['dir']."";
            $sql.= "  LIMIT ".$request->start." ,".$request->length."   ";

            $transactions = DB::select( DB::raw($sql) );
  
            }
            
           
            $totalFiltered = ($totalData);
            
        
               $rows = array();
               $data = array();
               //while( $rows=mysqli_fetch_assoc($query) ) {  // preparing an array
                  
               foreach($transactions as $row){
                   $nestedData=array();
                   foreach($row as $k=>$v)
                       $nestedData[] = $v;
                   
                  $data[] = $nestedData;
               }
               
    $json_data = array(
            "query"=>$sql,
            "draw"            => intval( $request->draw ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
            );
        return $json_data;
    }
    function download_nbc(Request $request){
    
       
        $sql = "SELECT transactions.id, fulltimestamp, terminal, members.fullname, members.ip_address, utility_type, amount,utility_reference, msisdn, reference, transid, result, message from transactions join members on members.id = transactions.id  ";
        $where = ' WHERE transactions.id = members.id' ;
        
       if (isset($request->fulltimestamp)){
            $fulltimestamp = $request->fulltimestamp;
            $range = explode('|',$fulltimestamp);		
            $start = trim($range[0]); //name
            $end = trim($range[1]); //name
            $where.=" AND fulltimestamp >= '".$start."' AND fulltimestamp < ('" .$end. "' + INTERVAL 1 DAY) ";
        }
        
        //if (isset($request->transid)){
            $transid = $request->transid;
            $where.=" AND (transid like '%" . $transid ."%' or reference like '%".$transid."%') ";
        //}		
       // if (isset($request->util_ref)){
            $util_ref = $request->util_ref;
            $where.=" AND utility_reference like '%" . $util_ref ."%'" ;
        //}
        //if (isset($request->result)){
            $result = $request->result;
            $where.=" AND result like '%" . $result ."%'" ;
       // }
        
        $sql .= $where;   
        
        
        $sql.= " ORDER BY fulltimestamp desc";
        DB::enableQueryLog();
            $transactions = DB::select( DB::raw($sql) );
        DB::getQueryLog();
        //$columns = DB::getSchemaBuilder()->getColumnListing('transactions');
        $columns = ["transactions.id", "fulltimestamp", "terminal", "fullname","ip_address", "utility_type", "amount","utility_reference", "msisdn", "reference", "transid", "result", "message"];
        
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
       
        $csv->insertOne($columns);
    
     

    foreach($transactions as $row) {
        $arr = array();
        foreach ($row as $key => $val)
            $arr[] = $val;
        $csv->insertOne($arr);
    }

    $csv->output('download_nbc.csv');
  

}

}

