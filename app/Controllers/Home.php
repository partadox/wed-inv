<?php

namespace App\Controllers;

class Home extends BaseController
{
	// public function __construct()
    // {
    //     $this->db = \Config\Database::connect();
    // }
    public function visitor()
	{
		//Get Datetime now
		$dt        = date("Y-m-d H:i:s");

		//Get IP
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //Checking IP From Shared Internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //To Check IP is Pass From Proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

        //Get Slug
        $uri    = service('uri');
        $slug   = $uri->getSegment(1);

        if ($slug != NULL) {
            $slug = $slug;
        } else {
            $slug = 'home';
        }
        

		
        $visitor = [
            'vs_ip'        => $ip,
            'vs_slug'      => $slug,
            'vs_dt'        => $dt,
        ];
        $this->visitor->insert($visitor);
		
	}

	public function not_found()
	{
		$this->cachePage(360);
		return view('errors/404');
	}

    public function index()
	{
        $this->visitor();
        //Get Slug
        $uri    = service('uri');
        $slug   = $uri->getSegment(1);
        
        $ucapan       = $this->ucapan->list();

        if ($slug != NULL) {

            $cek_undangan = $this->undangan->cek($slug);

            if ($cek_undangan != 0) {
               $ud      = $this->undangan->dt_undangan($slug);
               $ud_nama = $ud->ud_nama;

               $data = [
                'undangan'	=> $ud_nama,
                'ucapan'    => $ucapan,
            ];

            } else {
                $this->not_found();
            }
            
            

        } else {
            $data = [
                'undangan'	=> 'Tamu Undangan',
                'ucapan'    => $ucapan,
            ];
        }
        return view('index', $data);
	}

	// public function login()
	// {
	// 	if (session('login')) {
    //         return redirect()->to('home/index');
    //     }
	// 	$tgl  = date("d M Y");
	// 	$data = [
	// 		'class_body' 		=> 'class="body-scroll d-flex flex-column h-100" data-page="signin"',
	// 		'tgl'				=> $tgl,
	// 		'site_key' 			=> getenv('recaptchaKey'),
	// 	];
	// 	return view('v_login', $data);
	// }

	// public function validasi()
	// {
	// 	if ($this->request->isAJAX()) {
    //         $username 			= $this->request->getVar('username');
    //         $password 			= $this->request->getVar('password');
    //         $recaptchaResponse = trim($this->request->getVar('g-recaptcha-response'));

    //         $validation = \Config\Services::validation();

    //         $valid = $this->validate([
    //             'username' => [
    //                 'label' => 'Username',
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => '{field} wajib diisi!'
    //                 ]
    //             ],
    //             'password' => [
    //                 'label' => 'Password',
    //                 'rules' => 'required',
    //                 'errors' => [
    //                     'required' => '{field} wajib diisi!'
    //                 ]
    //             ]
    //         ]);

    //         if (!$valid) {
    //             $msg = [
    //                 'eror' => [
	// 					'respon' => 'Invalid Username or Password!',
	// 					'link'   => 'login'
	// 				]
    //             ];
    //         } else {

    //             //cek user
    //             $query_cekuser = $this->db->query("SELECT * FROM tb_user WHERE username='$username'");

    //             $result = $query_cekuser->getResult();

    //             $secret = getenv('recaptchaSecret');
    //             $credential = array(
    //                 'secret' => $secret,
    //                 'response' => $recaptchaResponse
    //             );

    //             $verify = curl_init();
    //             curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    //             curl_setopt($verify, CURLOPT_POST, true);
    //             curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
    //             curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
    //             curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    //             $response = curl_exec($verify);

    //             $status = json_decode($response, true);

                
    //             if (count($result) > 0) {
    //                 $row = $query_cekuser->getRow();
    //                 $password_user = $row->password;

    //                 if (password_verify($password, $password_user) && $status["success"]) {
    //                     if ($row->active == 1) {
    //                         $simpan_session = [
    //                             'login' 	=> true,
    //                             'user_id' 	=> $row->user_id,
    //                             'username' 	=> $username,
    //                             'name'  	=> $row->name,
    //                             'role' 		=> $row->role,
    //                         ];

    //                         $this->session->set($simpan_session);

    //                         $msg = [
    //                             'sukses' => [
    //                                 'link' => 'home'
    //                             ]
    //                         ];
    //                     } else {
    //                         $msg = [
    //                             'eror' => [
    //                                 'respon' => 'Inactive User',
    //                                 'link'   => 'login'
    //                             ]
    //                         ];
    //                     }
    //                 } else {
    //                     $msg = [
    //                         'eror' => [
    //                             'respon' => 'Username or Password 404',
    //                             'link'   => 'login'
    //                         ]
    //                     ];
    //                 }
    //             } else {
    //                 $msg = [
    //                     'eror' => [
    //                         'respon' => 'Username or Password 404',
    //                         'link'   => 'login'
    //                     ]
    //                 ];
    //             }
    //         }
    //         echo json_encode($msg);
			
	// 	}
	// }



	public function simpan_ucapan77()
    {
        if ($this->request->isAJAX()) {
			
			$simpandata = [
                'uc_nama'       => $this->request->getVar('name'),
                'uc_date'       => date("Y-m-d"),
                'uc_isi'        => $this->request->getVar('ucapan'),
                'uc_hadir'      => $this->request->getVar('kehadiran'),
                
            ];

            $this->ucapan->insert($simpandata);
        }
    }



}
