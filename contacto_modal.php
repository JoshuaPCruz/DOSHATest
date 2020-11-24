<?php
require 'config.php';
require "vendor/autoload.php";
    if (isset($_POST['submit'])) { 
		
        $secretKey = "6LeLx58UAAAAAJyWFh7VRTFHGM1H3LW6p9C-iYql";
        $responseKey = $_POST['g-recaptcha-response'];
        $userIP = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";

        $response = file_get_contents($url);
        $response = json_decode($response);

        if ($response->success) {
        	$utm_json = base64_decode($_POST['utm_json']);
	        $utm_array = json_decode($utm_json);
	        $utm_mesage = "";
			foreach ($utm_array as $clave=>$valor)
	   		{
	   			$utm_mesage .="$clave : $valor \n";
	   		}
			// Default Params
			$landingId = 'aceleradordigital';
			$leadSource = isset($utm_array->utm_source) ? $utm_array->utm_source. ' aceleradordigital' : 'aceleradordigital';
			$origin = 'Digital';
			$company = 'Unifin';
			$platform = isset($utm_array->utm_source) ? $utm_array->utm_source : 'No Definido';
			$campaign =  isset($utm_array->utm_campaign) ? $utm_array->utm_campaign : 'No Definido'; 

			// Form Params
			$name = $_POST['name'];
			$lastname1 = $_POST['lastname1'];
			$lastname2 = $_POST['lastname2'];
			$mail = $_POST['email'];
			$tel = $_POST['tel'];
//			$regimen = isset($_POST['regimen']);
			$regimen = isset($_POST['regimen']) ? $_POST['regimen'] : 'Persona Moral';
			$ingreso = isset($_POST['ingreso']) ? $_POST['ingreso'] : 'No Definido ingreso';
			$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : 'No Definido empresa';
			$monto = isset($_POST['monto']) ? $_POST['monto'] : 'Hasta 1 MDP';

			$fullName = $name.' '.$lastname1.' '.$lastname2;

			// Conditional Facebook fields
			$aux = '';
			if(isset($utm_array->utm_content)){
				$aux = explode("-", $utm_array->utm_content);
			}

			$ad_name = isset($aux[0]) ? $aux[0] : 'No Definido';
			$campaign_id = '';
			$fbclid = '';

			if(isset($_POST['campaign_id'])){
				$campaign_id = $_POST['campaign_id'];
			}
			if(isset($_POST['fbclid'])){
				$fbclid = $_POST['fbclid'];
			}
			
			$placement = isset($aux[1]) ? $aux[1] : 'No Definido';
			$utm_campaign = isset($utm_array->utm_campaign) ? $utm_array->utm_campaign : 'No Definido';
			$utm_content = isset($utm_array->utm_content) ? $utm_array->utm_content : 'No Definido';
			$utm_source = isset($utm_array->utm_source) ? $utm_array->utm_source : 'No Definido';
			$utm_medium = isset($utm_array->utm_medium) ? $utm_array->utm_medium : 'No Definido';
			$utm_term = isset($utm_array->utm_term) ? $utm_array->utm_term : 'No Definido';
			$campaign_id = isset($utm_array->campaignid) ? $utm_array->campaignid : 'No Definido';
			$keyword = isset($utm_array->keyword) ? $utm_array->keyword : 'No Definido';
			$gclid = isset($utm_array->gclid) ? $utm_array->gclid : 'No Definido';
			$clientID = isset($_POST['clientID']) ? $_POST['clientID'] : 'No Definido';
			$fbclid = isset($utm_array->fbclid) ? $utm_array->fbclid : 'No Definido';

			
			// Mail Params
			$subject = $fullName;
			$mailheader = "From: $mail \r\n";

			// Message
			$message = "
				ID Landing: $landingId\n
				Lead Source: $leadSource\n
				Origen: $origin \n
				Company: $company \n
				Platform: $platform \n
				Campaign: $campaign\n 

				GCLID: $gclid\n 
				Keyword: $keyword\n 
				clientID: $clientID\n 

				Nombre Contacto Clave: $fullName \n
				Email de la empresa: $mail \n
				Telefono de la empresa: $tel \n
				Regimen: $regimen \n
				Nombre de la empresa: $empresa \n
				Ingreso: $ingreso \n
				Monto solicitado: $monto \n
				\n
				ad_name: $ad_name \n
				campaign_id: $campaign_id \n
				fbclid: $fbclid \n
				placement: $placement \n
				utm_campaign: $utm_campaign \n
				utm_content: $utm_content \n
				utm_source: $utm_source \n
				\n";


			// $message .=$utm_mesage;
			$message = utf8_decode($message);

			// // mail($recipient, $subject, $message, $mailheader) or die("Error!");

			try {
				$client = new \GuzzleHttp\Client();
				$response = $client->post($urlAPI."/api/private/login", [
				    'headers' => [
				        'Content-Type' => 'application/json'
				    ],
				    'json' => [
				        'name' => $nameAPI,
				        'email' => $emailAPI,
				        'password' => $passwordAPI,
				    ]
				]);
			} catch (GuzzleHttp\Exception\ClientException $e) {
			    echo 'Authorization Failed name: '.$nameAPI. ' email:  '. $emailAPI.' password:  '.$passwordAPI .'    ------- '. $e->getMessage();
			}

			$data = json_decode($response->getBody());
			$token = "Bearer ".$data->access_token;

			$bitacoraSend = true;
			$gateSend = true;
			$nocrmSend = true;
			if($regimen === 'Persona Moral' || $regimen === 'persona moral') {
				$bitacoraSend = true;
				$gateSend = false;
				$nocrmSend = true;
			}
			/////jl

			else if ($req['regime'] == 'Persona Física con Actividad Empresarial' || $req['regime'] == 'pfae' || $req['regime'] == 'pfaeb'){
         
			//else{
				$bitacoraSend = true;
				$gateSend = true;
				$nocrmSend = true;

			}
/////end -jl
			try {
				$client2 = new \GuzzleHttp\Client();
				$bitacora = $client2->post($urlAPI."/api/private/lead/create", [
				    'headers' => [
				        'Content-Type' => 'application/json',
				        'Authorization' => $token
				    ],
				    'json' => [
				    	"company" => $company,
				    	"bitacoraSend" => $bitacoraSend,
				    	"gateSend" => $gateSend,
				    	"nocrmSend" => $nocrmSend,
				        "name" => $name,
					    "lastname1" => $lastname1,
					    "lastname2" => $lastname2,
					    "email" => $mail,
					    "phone" => $tel,
					    "regime" => $regimen,
					    "company_name" => $empresa,
					    "annual_income" => $ingreso,
					    "credit_amount" => $monto,
					    "origin" => $landingId,
					    "utm_source" => $utm_source,
					    "utm_medium" => $utm_medium,
					    "utm_campaign" => $utm_campaign,
					    "utm_term" => $utm_term,
						"utm_content" => $utm_content,
						"clientID" => $clientID,
						"gclid" => $gclid,
						"campaign_id" => $campaign_id,
						"keyword" => $keyword,
						"fbclid" => $fbclid,
						"lead_source" => $leadSource,
						"ad_name" => $ad_name,
						"type_credit" => 'No definido',
						"landingId" => $landingId
				    ]
				]);
			} catch (GuzzleHttp\Exception\ClientException $e) {
			    echo 'API error ' . $e->getMessage();
			}
			
			
			echo ("<script LANGUAGE='JavaScript'>
					window.location.href='gracias.php';
					</script>");
			}
        else
			// If CAPTCHA is NOT completed...
            echo ("<script LANGUAGE='JavaScript'>
					window.location.href='spam.php';
					</script>");
    }


?>